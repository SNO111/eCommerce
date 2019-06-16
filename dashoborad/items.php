<?php
    session_start();
    if($_SESSION['username']) {
        include('init.php');
        $pageTitle = 'Items';


        $page = isset($_GET['page']) ? $_GET['page'] : 'Manage';

        if ($page == 'Manage') { // Manage Page
            echo '<main>';
            $items = getAllFrom("*", "items", '', '', 'id');

        ?>
                <h1 class="heading">items</h1>
                <div class="container">
                    <a class="btn-add" href="?page=Add"><i class="fa fa-plus"></i>Add Item</a>
                    <section class="section-wrap">
                        <!--for demo wrap-->

                        <div class="tbl-header">
                            <table cellpadding="0" cellspacing="0">
                            <thead>
                                <tr>
                                <td>Image</td>
                                <th>Name</th>
                                <th>Price</th>
                                <th>description</th>
                                <th>Tags</th>
                                <th>Manage</th>
                                </tr>
                            </thead>
                            </table>
                        </div>
                        <div class="tbl-content">
                            <table cellpadding="0" cellspacing="0">
                            <?php foreach ($items as $item) { ?>
                            <tbody>
                                <tr>
                                    <td>
                                        <?php if(!empty($item['item_image'])) { ?>
                                        <img class="item-profile img-responsive" src="uploaded/images/<?php echo $item['item_image'];?>" alt="">
                                        <?php  } else {
                                            echo '<img class="item-profile img-responsive" src="uploaded/images/profile.png" alt="">';
                                        } ?>
                                    </td>
                                    <td><?php echo $item['name']; ?></td>
                                    <td>$<?php echo $item['price']; ?></td>
                                    <td>
                                        <?php 
                                                // Break Long Text into short paragraph
                                                $text = substr($item['description'], 0, 120);
                                                echo $text .
                                                    '<br /><a class="see-more" href="?page=View&itemid=' . 
                                                    $item['id'] . '">See More...</a>';
                                            ?>
                                    </td>
                                    <td><?php echo $item['item_tags']; ?></td>
                                    <td>
                                        <a class="button button-blue" href="?page=Edit&itemid=<?php echo $item['id'];?>">
                                            <i class="fa fa-globe"></i>
                                            <span class="edit">Edit</span>
                                        </a>
                                        <a id="alert" class="button button-red confirm" href="?page=Delete&itemid=<?php echo $item['id'];?>">
                                            <i class="fa fa-close"></i>
                                            <span class="delete">Delete</span>
                                        </a>
                                        <!--  Select View Button to show Item information -->
                                        <a class="button button-green" href="?page=View&itemid=<?php echo $item['id'];?>">
                                            <i class="fa fa-eye"></i> 
                                            <span class="View">View</span>
                                            
                                        </a>
                                    </td>

                                </tr>
                            </tbody>
      
                            <?php } ?>
                            </table>
                        </div>
                    </section>


                </div>
            </main>


  <?php } elseif ($page == 'Add') { ?>
            <main>
                <h1 class="heading">add Item</h1>
                <div class="container">
                    <form class="form-style-7" action="?page=Insert" method="POST" enctype="multipart/form-data">
                        <ul>
                             <li class="text-center">
                                <label>Selecet item image</label>
                                <img id="output" class="preview-img">
                                <br>
                                <input type="file" name="image"  accept="image/*" onchange="loadFile(event)">
                                <br>
                            </li>
                            <li>
                                <label for="name">Name</label>
                                <input type="text" name="name" maxlength="100" >
                                <span>Enter your full name here</span>
                            </li>
                            <li>
                                <label for="price">Price</label>
                                <input type="text" name="price" maxlength="100" >
                                <span>Give a price for this item</span>
                            </li>
                            <li>
                                <label for="description">Description</label>
                                <textarea name="description" onkeyup="adjust_textarea(this)"></textarea>
                                <span>Say something about the item</span>
                            </li>
                            <li>
                                <label for="category">Category</label>
                                <select name="cat">
                                     <option value="0">--</option>
                                     <?php 
                                     $stmt = $db->prepare("SELECT * FROM categories WHERE parent = 0");
                                     $stmt->execute();
                                     $cats = $stmt->fetchAll();
                                     foreach($cats as $cat) { ?>
                                    <option value="<?php echo $cat['id'];?>"><?php echo $cat['name'];?></option>
                                    <?php } ?>
                                 </select>                                
                                 <span>Choose category</span>
                            </li>
                            <li>
                                 <label for="tags">Tags</label>
                                <input type="text" name="tags" maxlength="100" >
                                <span>Enter Tags, separate by [ / ] or [ & ]</span>
                            </li>
                            <li>
                                <input class="btn-add" type="submit" value="Add Item" >
                            </li>
                          </ul>
                    </form>
                </div>
            </main>



        <?php } elseif ($page == 'Insert') {
                echo '<main>';
                echo '<h1 class="heading">Insert Items!</h1>';
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
                    $name   = $_POST['name'];
                    $desc   = $_POST['description'];
                    $price  = $_POST['price'];
                    $tags   = $_POST['tags']; 
                    $cat    = $_POST['cat'];

     

                    //  Check if there is no error proceed the insert operation
                    $formErrors = array();
                    if(empty($name)) {
                        $formErrors[] = 'Insert a name first to continue';
                    }
                    if (empty($price)) {
                        $formErrors[] = 'Insert a price for the item';
                    }
                    if ($cat == 0) {
                        $formErrors[] = 'Choose category please';
                    }
                    if (empty($imageName)) {
                        $formErrors[] = 'Insert an image for this item';
                    }
                    if ( !empty($imageName) &&!in_array($imageextensions, $allowedExtensions)) {
                        $formErrors[] = 'Extension not allowed, please choose a JPEG or PNG file.';
                    } 
                    if ($imageTmp > 4194304 ) {
                        $formErrors[] = 'File size must be excately 4 MB or less';
                    }
                    foreach ($formErrors as $error) {
                        $msgStatus = '<div class="alert-error">' . $error . '</div>';
                        reDirecthome($msgStatus, 'back');
                        
                    }


                    // Update the Database with these new info
                    if (empty($formErrors)) {

                        $image =rand(0, 1000000) . '_' . $imageName;

                        move_uploaded_file($imageTmp, 'uploaded\images\\' . $image);

                            $stmt = $db->prepare("INSERT INTO  items (name, description, price, item_image, item_tags, cat_item_id, date) VALUES (:zname, :zdesc, :zprice, :zimage, :ztags,:zcatid, now())");
                            $stmtCount = $stmt->execute(array(
                                'zname'     => $name,
                                'zdesc'     => $desc,
                                'zprice'    => $price,
                                'zimage'    => $image,
                                'ztags'     => $tags,
                                'zcatid'    => $cat
                            ));

                            // Echo success message
                            if ($stmtCount) {
                                $msgStatus = '<div class="alert-success">Category Added</div>';
                                reDirecthome($msgStatus, 'back');
                            } else {
                                $msgStatus = '<div class="alert-error">Something went wrong!</div>';
                                reDirecthome($msgStatus, 'back');
                            }
                     }


                } else {
                    $msgStatus = '<div class="alert-error">Sorry, you can\'t browse this page directly</div>';
                    reDirecthome($msgStatus);
                }

                echo '</div>';
                echo '</main>';


        } elseif ($page == 'Edit') {
            $itemid = isset($_GET['itemid']) & is_numeric($_GET['itemid']) ? $_GET['itemid'] : 0;
            $items = getAllFrom("*", "items", "WHERE id = {$itemid}", '', 'id');

        ?>
        <main>
            <h1 class="heading">Edit Item</h1>
            <div class="container">
                <form class="form-style-9" action="?page=Update" method="POST" enctype="multipart/form-data">
                    <?php foreach ($items as $item) { ?>
                    <ul>
                        <li class="text-center">
                            <?php 
                            if(!empty($item['item_image'])) {
                                echo  '<img class="img-responsive img-item" src="uploaded/images/' . $item['item_image'] . '" alt=""><br>';
                            }

                            ?>
                            <img id="output" class="preview-img">
                            <br>
                            <input type="file" name="image"  accept="image/*" onchange="loadFile(event)">
                            <br>

                        </li>
                        <li>
                            <input type="hidden" name="itemid" value="<?php echo $itemid;?>">
                        </li>
                        <li>
                            <input type="text" name="name" class="field-style field-full align-left" placeholder="Item Name" value="<?php echo $item['name'] ?>" required />
                        </li>
                        <li>
                            <input type="text" name="price" class="field-style field-full align-left" placeholder="Item Price" value="<?php echo $item['price'] ?>" required />
                        </li>
                        <li>
                            <textarea name="description" class="field-style" placeholder="Description"><?php echo $item['description']; ?></textarea>
                        </li>
                        <li>
                            <select name="cat" class="field-style field-full align-left">
                                <?php 
                                $stmt = $db->prepare("SELECT * FROM categories");
                                $stmt->execute();
                                $cats = $stmt->fetchAll();
                                foreach ($cats as $cat) {
                                   echo '<option vlaue="' . $cat['id'] . '"';
                                   if ($item['cat_item_id'] == $cat['id']) {
                                       echo 'selected';
                                   }
                                   echo '>' . $cat['name'] . '</option>';
                                }
                                ?>
                            </select>
                        </li>
                        <li>
                            <input type="text" name="tags" class="field-style field-full align-left" placeholder="Enter Tags, separate by [ / ] or [ & ]" value="<?php echo $item['item_tags'];?>'">
                        </li>
                        <li>
                            <input class="btn-submit" type="submit" value="Save Edit" />
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
               $name        = $_POST['name'];
               $price       = $_POST['price'];
               $desc        = $_POST['description'];
               $itemid      = $_POST['itemid'];
               $tags        = $_POST['tags']; 
               $cat         = $_POST['cat'];
                
                //  Check if there is no error proceed the insert operation
                $formErrors = array();
                if(empty($name)) {
                    $formErrors[] = 'Insert a name first to continue';
                }
                if(empty($desc)) {
                    $formErrors[] = 'Insert a description';
                }
                if(empty($price)) {
                    $formErrors[] = 'Insert a price for this';
                }

                if ( !empty($imageName) &&!in_array($imageextensions, $allowedExtensions)) {
                    $formErrors[] = 'Extension not allowed, please choose a JPEG or PNG file.';
                } 
                if ($imageTmp > 4194304 ) {
                    $formErrors[] = 'File size must be excately 4 MB or less';
                }
                foreach ($formErrors as $error) {
                    echo '<div class="alert-error">' . $error . '</div>';
                }

                if (empty($formErrors)) {

                    $image = rand(0, 1000000) . '_' . $imageName;

                    move_uploaded_file($imageTmp, 'uploaded\images\\' . $image);

                    // Update the Database with these new info
                    $stmt = $db->prepare('UPDATE items SET name = ?, description = ?, price = ?, item_image = ?, item_tags = ?, cat_item_id = ? WHERE id = ?');
                    $stmtCount = $stmt->execute(array($name, $desc, $price, $image, $tags, $cat, $itemid));

                    // Echo success message
                    if ($stmtCount) {
                        $msgStatus = '<div class="alert-success">Category updated</div>';
                        reDirecthome($msgStatus, 'back');
                    } else {
                        $msgStatus = '<div class="alert-error">Something went wrong!</div>';
                        reDirecthome($msgStatus, 'back');
                        
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
                $itemid = isset($_GET['itemid']) & is_numeric($_GET['itemid']) ? $_GET['itemid'] : 0;

                $stmt = $db->prepare("SELECT * FROM items WHERE id = ?");
                
                $stmt->execute(array($itemid));
               
                // The Row count
                $count = $stmt->rowCount();

                // If there is such ID update the database
                if($count > 0) {    

                    $stmt = $db->prepare("DELETE FROM items WHERE id = :zitem ");
                    
                    $stmt->bindParam('zitem', $itemid);

                    $stmt->execute();

                    // Echo success message 
                    $msgStatus = '<div class="alert-success">Item deleted</div';
                    reDirecthome($msgStatus, 'back');

                } else {
                    $msgStatus = '<div class="alert-error">This ID is not exist</div>';
                    reDirecthome($msgStatus);
                }

                echo '</div>';
                echo '</main>';
         
        
        } elseif ($page == 'View') {
           echo '<main>';
           $itemid = isset($_GET['itemid']) & is_numeric($_GET['itemid']) ? $_GET['itemid'] : 0;

           $items = getAllFrom("*", "items", "WHERE id = {$itemid}", '', 'id');


                ?>
                
                <div class='detail-item'>
                <?php foreach ($items as $singleItem) {?>
                    <div class='detail-container'>
                         <h1 class="heading"><?php echo $singleItem['name'];?></h1>
                        <dl>
                  
                            <dd class="dd-img"><img class="img-responsive" src="uploaded/images/<?php echo $singleItem['item_image'];?>" alt=""></dd>
                            <dt>Name </dt>
                            <dd><?php echo $singleItem['name'];?></dd>
                            <dt>Description</dt>
                            <dd><?php echo $singleItem['description'];?></dd> 
                            <dt>Added</dt>
                            <dd><?php echo $singleItem['date'];?></dd> 
                                          
                        </dl>
                    
                    </div>
                    <?php } ?>         
                    <div class='detail-nav'>
                        <button class='see'>
                            <a href="..//single-item.php">View in websit page</a>
                        </button>
                        <button class='back' onclick="history.go(-1);">Back</button>
                    </div>            
                </div> 
                <?php
       

            echo '</main>';
        }  else {

            header('Location: index.php');
             exit();

        }   







        include $tpl . 'footer.php';
    } else {
        header('Location: index.php');
        exit();
    }
    ?>











