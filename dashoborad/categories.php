<?php 
    session_start();
    if($_SESSION['username']) {
        include('init.php');
        $pageTitle = 'Categories';


        $page = isset($_GET['page']) ? $_GET['page'] : 'Manage';
        
        if ($page == 'Manage') { // Manage Page 
            echo '<main>';

            $categories = getAllFrom("*", "categories", '', '', 'id');
       ?>
                <h1 class="heading">Categories</h1>
                <div class="container">
                    <a class="btn-add" href="?page=Add"><i class="fa fa-plus"></i>add Category</a>
                    <section class="section-wrap">
                        <div class="tbl-header">
                            <table cellpadding="0" cellspacing="0">
                            <thead>
                                <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Category Type</th>
                                <th>Tags</th>
                                <th>Manage</th>
                                </tr>
                            </thead>
                            </table>
                        </div>
                        <div class="tbl-content">
                            <table cellpadding="0" cellspacing="0">
                            <?php foreach ($categories as $cat) { ?>
                            <tbody>
                                <tr>
                                    <td><?php echo $cat['name']; ?></td>
                                    <td><?php echo  $cat['description']; ?></td>
                                    <td>
                                        <?php if($cat['parent'] == 0) {
                                                echo 'None';
                                            } else {
                                                echo 'Sub Category';
                                            }
                                        ?>
                                    </td>
                                    <td><?php echo  $cat['tags']; ?></td>
                                    <td>
                                        <a class="button button-blue" href="?page=Edit&catid=<?php echo $cat['id'];?>">
                                            <i class="fa fa-globe"></i>
                                            <span class="edit">Edit</span>
                                        </a>
                                        <a id="alert" class="button button-red confirm" href="?page=Delete&catid=<?php echo $cat['id'];?>">
                                            <i class="fa fa-close"></i>
                                            <span class="delete">Delete</span>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                       <?php } ?>
                    </table>
                    </div>
                </div>
            </main>
                            

  <?php } elseif ($page == 'Add') { ?>
            <main>
                <h1 class="heading">add category</h1>
                <div class="container">
                    <form class="form-style-7" action="?page=Insert" method="POST" enctype="multipart/form-data">
                        <ul>
                            <li class="text-center">
                                <label>Selecet an image</label>
                                <img id="output" class="preview-img">
                                <br>
                                <input type="file" name="image"  accept="image/*" onchange="loadFile(event)">
                                <br>
                            </li>
                            <li>
                                <label for="name">Name</label>
                                <input type="text" name="name" maxlength="100" >
                                <span>Enter a xategory name</span>
                            </li>
                            <li>
                                <label for="description">Description</label>
                                <input type="text" name="description" maxlength="100" >
                                <span>Enter description</span>
                            </li>
                            <li>
                                <label for="parent">Parent: </label>
                                <select name="parent">
                                    <option value="0">None</option>
                                    <?php 
                                        $allCats = getAllFrom('*', 'categories', 'WHERE parent = 0', '', 'id');
                                        foreach ($allCats as $cat) {?>
                                        <option value="<?php echo $cat['id'];?>"><?php echo $cat['name'];?></option>
                                            
                                    <?php } ?>

                      
                                </select>
                                <span>Enter description</span>
                            </li>
                            <li>
                                <label for="tags">Tags</label>
                                <input type="text" name="tags" maxlength="100" >
                                <span>Enter Tags, separate by [ / ] or [ & ]</span>
                            </li>
                            <li>
                                <input class="btn-add" type="submit" value="Add Category" >
                            </li>
                          </ul>
                    </form>
                </div>
            </main>


        <?php } elseif ($page == 'Insert') {
                echo '<main>';
                echo '<h1 class="heading">Insert Category!</h1>';
                echo '<div class="container">';

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    // Get File variables
                    $imageName  = $_FILES['image']['name'];
                    $imageSize  = $_FILES['image']['size'];
                    $imageTmp   = $_FILES['image']['tmp_name'];
                    $imageType  = $_FILES['image']['type'];

                    // List of allowed File type to upload  
                    $allowedExtensions = array('jpg','jpeg','png');

                    // Get Image Extention and explode it
                    $imageNameExplode = explode('.', $imageName); 
                    $imageextensions  = strtolower(end($imageNameExplode));

                    // Get variables sfrom the form 
                    $name       = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
                    $desc       = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
                    $parent     = $_POST['parent'];
                    $tags       = filter_var($_POST['tags'],FILTER_SANITIZE_STRING);

                    //  Check if there is no error proceed the insert operation
                    $formErrors = array();
                    if(empty($name)) {
                        $formErrors[] = 'Insert a name first to continue';
                    }
                    if(empty($desc)) {
                        $formErrors[] = 'Insert a description';
                    }
                    if ( !empty($imageName) &&!in_array($imageextensions, $allowedExtensions)) {
                        $formErrors[] = 'Extension not allowed, please choose a JPEG or PNG file.';
                    } 
                    if ($imageTmp > 4194304 ) {
                        $formErrors[] = 'File size must be excately 4 MB or less';
                    }
                    foreach ($formErrors as $error) {
                        $msgStatus = '<div class="alert-error">' . $error . '</div>';
                        reDirecthome($msgStatus,'back', 10);
                    }

                    // Update the Database with these new info
                    if (empty($formErrors)) {

                        $image =rand(0, 1000000) . '_' . $imageName;

                        move_uploaded_file($imageTmp, 'uploaded\images\\' . $image);

                            $stmt = $db->prepare("INSERT INTO  categories (name, description, cat_image, parent) VALUES (:zname, :zdesc, :zimage, :zparent)");
                            $stmtCount = $stmt->execute(array(
                                'zname'     => $name, 
                                'zdesc'     => $desc,
                                'zimage'    => $image,
                                'zparent'   => $parent
                            ));

                            // Echo success message 
                            if ($stmtCount) {
                                $msgStatus  = '<div class="alert-success">Category Added</div>';
                                reDirecthome($msgStatus,'back');
                            } else {
                                $msgStatus = '<div class="alert-error">Something went wrong!</div>';
                                reDirecthome($msgStatus,'back');
                            }
                     }


                } else {
                    $msgStatus = '<div class="alert-error">Sorry, you can\'t browse this page directly</div>';
                    reDirecthome($msgStatus); 

                }

                echo '</div>';
                echo '</main>';
  

        } elseif ($page == 'Edit') { 
            echo '<main>';
            $catid = isset($_GET['catid']) & is_numeric($_GET['catid']) ? $_GET['catid'] : 0;
            $categories = getAllFrom("*", "categories", "WHERE id = {$catid}", '', 'id');
            
        ?>
            <h1 class="heading">Edit Category</h1>
            <div class="container">
                <form class="form-style-7" onsubmit="return submitForm(this);" action="?page=Update" method="POST" enctype="multipart/form-data">
                   <?php foreach ($categories as $cat) { ?>
                    <ul>
                           <input type="hidden" name="catid" value="<?php echo $catid;?>">
                        <li class="text-center">
                            <label for="image">Select an image</label>
                            <?php if(!empty($cat['cat_image'])) { ?>
                                <img class="img-responsive" src="uploaded/images/<?php echo $cat['cat_image'];?>" alt="">
                            <?php  }  ?>
                            <img id="output" class="preview-img">
                            <br>
                            <input type="file" name="image"  accept="image/*" onchange="loadFile(event)">
                        </li>
                        <li>
                            <label for="name">Name</label>
                            <input type="text" name="name" maxlength="100" value="<?php echo $cat['name'] ?>" >
                            <span>Enter a xategory name</span>
                        </li>
                        <li>
                            <label for="description">Description</label>
                            <textarea name="description"><?php echo $cat['description']; ?></textarea>
                            <span>Enter description</span>
                        </li>
                        <li>
                            <label for="description">Parent: </label>
                            <select name="parent">
                                <option value="0">None</option>
                                <?php $allCats = getAllFrom('*', 'categories', 'WHERE parent = 0', '', 'id');
                                        foreach ($allCats as $cat) {?>
                                        <option value="<?php echo $cat['id'];?>"><?php echo $cat['name'];?></option>
                                            
                                    <?php } ?>

                            </select>
                            <span>Enter description</span>
                        </li>
                        <li>
                            <label for="tags">Tags</label>
                            <input type="text" name="tags" maxlength="100" >
                            <span>Enter Tags, separate by [ / ] or [ & ]</span>
                        </li>
                        <li>
                            <input class="btn-add" type="submit" value="Add Category" >
                        </li>
                    </ul>
                    <?php } ?>
                </form>
            </div>
        </main>

       <?php } elseif ($page == 'Update') { 
           echo '<main>';
           echo '<h1 class="heading">Update!</h1>';
           echo '<div class="container">';
           if ($_SERVER['REQUEST_METHOD'] == 'POST') {

              // Get File variables
              $imageName  = $_FILES['image']['name'];
              $imageSize  = $_FILES['image']['size'];
              $imageTmp   = $_FILES['image']['tmp_name'];
              $imageType  = $_FILES['image']['type'];

              // List of allowed File type to upload  
              $allowedExtensions = array('jpg','jpeg','png');

              // Get Image Extention and explode it
              $imageNameExplode = explode('.', $imageName); 
              $imageextensions  = strtolower(end($imageNameExplode));

               // Get variables sfrom the form 
               $name    = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
               $desc    = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
               $catid   = $_POST['catid'];
               $parent  = $_POST['parent'];
               $tags    = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);

                //  Check if there is no error proceed the insert operation
                $formErrors = array();
                if(empty($name)) {
                    $formErrors[] = 'Insert a name first to continue';
                }
                if(empty($desc)) {
                    $formErrors[] = 'Insert a description';
                }
                if(empty($tags)) {
                    $formErrors[] = 'Make sure to insert some tags separat by / or & ';
                }
                if ( !empty($imageName) &&!in_array($imageextensions, $allowedExtensions)) {
                    $formErrors[] = 'Extension not allowed, please choose a JPEG or PNG file.';
                } 
                if ($imageTmp > 4194304 ) {
                    $formErrors[] = 'File size must be excately 4 MB or less';
                }

                foreach ($formErrors as $error) {
                    $msgStatus = '<div class="alert-error">' . $error . '</div>';
                    reDirecthome($msgStatus,'back', 5);
                }

                if (empty($formErrors)) {

                    $image =rand(0, 1000000) . '_' . $imageName;

                    move_uploaded_file($imageTmp, 'uploaded\images\\' . $image);
                    // Update the Database with these new info
                    $stmt = $db->prepare("UPDATE categories SET name = ?, description = ?, cat_image = ?, parent = ? WHERE id = ?");
                    $stmtCount = $stmt->execute(array($name, $desc, $image, $parent, $catid));

                    // Echo success message 
                    if ($stmtCount) {
                        $msgStatus = '<div class="alert-success">Category updated</div>';
                        reDirecthome($msgStatus,'back');
                    } else {
                        $msgStatus = '<div class="alert-error">Something went wrong!</div>';
                        reDirecthome($msgStatus);
                }
            }

           } else {
               $msgStatus = '<div class="alert-error">Sorry, you can\'t browse this page directly</div>';
               reDirecthome($msgStatus);
           }
           echo '</div>';
           echo '</main>';
           
           
        ?>

        <?php  } elseif ($page == 'Delete') {
            
            echo '<main>';
            echo '<h1 class="heading">Delete!</h1>';
            echo '<div class="container">';

            // Check if th eGet Request itemid is numeric & get the integer value of if
            $catid = isset($_GET['catid']) & is_numeric($_GET['catid']) ? $_GET['catid'] : 0;

            $stmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
            
            $stmt->execute(array($catid));
           
            // The Row count
            $count = $stmt->rowCount();

            // If there is such ID update the database
            if($count > 0) {    

                $stmt = $db->prepare("DELETE FROM categories WHERE id = :zcat ");
                
                $stmt->bindParam('zcat', $catid);

                $stmt->execute();

                // Echo success message 
                $msgStatus = '<div class="alert-success">Category deleted</div';
                reDirecthome($msgStatus, 'back');

            } else {
                $msgStatus = '<div class="alert-error">This ID is not exist</div>';
                reDirecthome($msgStatus);
                
            }

            echo '</div>';
            echo '</main>';
     
    
        } else {

            header('Location: index.php');
             exit();

        }


        include $tpl . 'footer.php';
    } else {
        header('Location: index.php');
        exit();
    }
    ?>




    






