<?php
    session_start();
    if($_SESSION['username']) {
        include('init.php');
        $pageTitle = 'Items';


        $page = isset($_GET['page']) ? $_GET['page'] : 'Manage';

        if ($page == 'Manage') { // Manage Page
            echo '<main>';
            $customers = getAllFrom("*", "users", '', '', 'id');
        ?>
                <h1 class="heading">Customers</h1>
                <div class="container">
                    <a class="btn-add" href="?page=Add"><i class="fa fa-plus"></i>Add Customers</a>
                    <section class="section-wrap">
                        
                        <div class="tbl-header">
                            <table cellpadding="0" cellspacing="0">
                            <thead>
                                <tr>
                                <th>User Avatar</th>
                                <th>Username</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Manage</th>
                                </tr>
                            </thead>
                            </table>
                        </div>
                        <div class="tbl-content">
                            <table cellpadding="0" cellspacing="0">
                            <?php foreach ($customers as $customer) { ?>
                            <tbody>
                                <tr>
                                <td>
                                    <?php if(!empty($customer['user_image'])) { ?>
                                    <img class="user-profile img-responsive" src="uploaded/images/<?php echo $customer['user_image'];?>" alt="">
                                    <?php  } else {
                                        echo '<img class="user-profile img-responsive" src="uploaded/images/profile.png" alt="">';
                                    } ?>
                                </td>
                                <td><?php echo $customer['username']; ?></td>
                                <td><?php echo $customer['fullname']; ?></td>
                                <td><?php echo $customer['email'];?></td>
                                <td>
                                    <a class="button button-blue" href="?page=Edit&userid=<?php echo $customer['id'];?>">
                                        <i class="fa fa-globe"></i>
                                        <span class="edit">Edit</span>
                                    </a>
                                    <a id="alert" class="button button-red confirm" href="?page=Delete&userid=<?php echo $customer['id'];?>">
                                        <i class="fa fa-close"></i>
                                        <span class="delete">Delete</span>
                                    </a>
                                    <!--  Select View Button to show customer info -->
                                    <a class="button button-green" href="?page=View&userid=<?php echo $customer['id'];?>">
                                        <i class="fa fa-eye"></i>
                                        <span class="view">view</span>
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
                <h1 class="heading">Add Customer</h1>
                <div class="container">
                    <form class="form-style-7" action="?page=Insert" method="POST" enctype="multipart/form-data">
                        <ul>
                            <li class="text-center">
                                <label>Selecet a profile image</label>
                                <img id="output" class="preview-img">
                                <br>
                                <input type="file" name="image" accept="image/*" onchange="loadFile(event)">
                                <br>
                            </li>
                            <li>
                                <label for="username">Username</label>
                                <input type="text" name="username" maxlength="100" >
                                <span>Enter a chosen Username here</span>
                            </li>
                            <li>
                                <label for="fullname">Full Name</label>
                                <input type="text" name="fullname" maxlength="100" >
                                <span>Enter you full name here</span>
                            </li>
                            <li>
                                <label for="email">E-mail</label>
                                <input type="email" name="mail" maxlength="100" >
                                <span>Enter you E-mail please</span>
                            </li>
                            <li>
                                <label for="password">Password</label>
                                <input type="password" name="password" maxlength="100" >
                                <span>Enter you Password</span>
                            </li>
                            <li>
                                <label for="confirm_password">Confirm Password</label>
                                <input type="password" name="confirm_password" maxlength="100" >
                                <span>Enter your Repeat Password please</span>
                            </li>
                            <li>
                                <label for="phone">Phone Number</label>
                                <input type="text" name="phone" maxlength="100" >
                                <span>Enter your Phone Number to receive a SMS after signup</span>
                            </li>
                            <li>
                                <input class="btn-add" type="submit" value="Add Customer" >
                            </li>
                          </ul>
                    </form>
                </div>
            </main>



        <?php } elseif ($page == 'Insert') {
                echo '<main>';
                echo '<h1 class="heading">Insert customer!</h1>';
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
                    $user           = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
                    $fullname       = filter_var($_POST['fullname'], FILTER_SANITIZE_STRING);
                    $mail           = filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL);
                    $pass           = $_POST['password'];
                    $hashedPass     = sha1($pass);
                    $phone          = filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT);


                    //  Check if there is no error proceed the insert operation
                    $formErrors = array();
                    if(empty($user)) {
                        $formErrors[] = 'Make sure to choose a Username';
                    }
                    if (strLen($user) < 4 || strLen($user) > 20) {
                        $formErrors[] = 'Username can\'t be less than 4 characters';
                    }
                    if (empty($fullname)) {
                        $formErrors[] = 'Full Name Field can\'t be empty';
                    }
                    if(empty($mail)) {
                        $formErrors[] = 'E-mail Field can\'t be empty';
                    }
                    if (empty($pass)) {
                        $formErrors[] = 'Password Field can\'t be empty';
                    }
                    if ($_POST["password"] !== $_POST["confirm_password"]) {
                        $formErrors[] = 'Passwords do no match';
                    }
                    if (empty($phone)) {
                        $formErrors[] = 'Phone Field can\'t be empty';
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

                            $stmt = $db->prepare("INSERT INTO  users (username, fullname, email, password, phone, user_image, Group_id, date) 
                                                  VALUES (:zuser, :zfullname, :zemail, :zpass, :zphone, :zimage, 1, now())");
                            $stmtCount = $stmt->execute(array(
                                'zuser'     => $user,
                                'zfullname' => $fullname,
                                'zemail'    => $mail,
                                'zpass'     => $hashedPass,
                                'zphone'    => $phone,
                                'zimage'    => $image
                            ));
                            if ($stmt->rowCount() > 0 ) {
                                echo 'This username is exists aleardy!';
                            }
                            // Echo success message
                            if ($stmtCount) {
                                echo '<div class="alert-success">Customer Added</div>';
                            } else {
                                echo '<div class="alert-error">Something went wrong!</div>';
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
            $userid = isset($_GET['userid']) & is_numeric($_GET['userid']) ? $_GET['userid'] : 0;
            $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute(array($userid));
            $users = $stmt->fetchAll();



        ?>
        
            <h1 class="heading">Edit Customer</h1>
            <div class="container">
                <form class="form-style-7" action="?page=Update" method="POST" enctype="multipart/form-data">
                <?php foreach ($users as $user) { ?>
                    <ul>

                        <input type="hidden" name="userid" value="<?php echo $userid;?>">

                        <li class="text-center">
                        <label>Selecet a profile image</label>
                            <?php 
                                if(!isset($user['user_image'])) {
                                    echo  '<img class="img-responsive img-item" src="uploaded/images/profile.png" alt="">';

                                } else {
                                    echo  '<img class="img-fuild img-item" src="uploaded/images/' . $user['user_image'] . '" alt=""><br>';
                                }

                            ?>
                            <br>
                            <input type="file" name="image">
                            

                        </li>
                        <li>
                            <label for="username">Username</label>
                            <input type="text" name="username" maxlength="100" value="<?php echo $user['username'];?>">
                            <span>Username</span>
                        </li>
                        <li>
                            <label for="fullname">Full Name</label>
                            <input type="text" name="fullname" maxlength="100" value="<?php echo $user['fullname'];?>">
                            <span>Full Name</span>
                        </li>
                        <li>
                            <label for="email">E-mail</label>
                            <input type="email" name="mail" maxlength="100" value="<?php echo $user['email'];?>">
                            <span>E-mail</span>
                        </li>
                        <li>
                            <label for="password">Password</label>
                            <input type="hidden" name="oldPassword" maxlength="100" value="<?php echo $user['password'];?>">
                            <input type="password" name="newPassword" maxlength="100" >
                            <span>Password or leave it empty if you don't want to change it</span>
                        </li>
                        <li>
                            <label for="confirm_password">Confirm Password</label>
                            <input id="confirm_password" type="password" name="confirm_password" maxlength="100" >
                            <span>Enter your Repeat Password please or leave it as it if you don't want to change it</span>
                        </li>
                        <li>
                            <label for="phone">Phone Number</label>
                            <input type="text" name="phone" maxlength="100" value="<?php echo $user['phone'];?>">
                            <span>Enter your Phone Number to receive a SMS after signup</span>
                        </li>
                        <li>
                            <input class="btn-add" type="submit" value="Add User" >
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
               $userid      = $_POST['userid'];
               $user        = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
               $fullname    = filter_var($_POST['fullname'], FILTER_SANITIZE_STRING);
               $mail        = filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL);
               $pass        = '';
               if(empty($_POST['newPassword'])) {
                $pass = $_POST['oldPassword'];
               } else {
                $pass = sha1($_POST['newPassword']);
               }
               $phone = $_POST['phone'];



                //  Check if there is no error proceed the insert operation
                $formErrors = array();
                if(empty($user)) {
                    $formErrors[] = 'Choose your username';
                }
                if(empty($fullname)) {
                    $formErrors[] = 'Insert your Full Name';
                }
                if(empty($mail)) {
                    $formErrors[] = 'Insert your E-mail';
                }
                if ($_POST["newPassword"] != $_POST["confirm_password"]) {
                    $formErrors[] = 'Passwords do no match';
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

                if (empty($formErrors)) {

                    $image =rand(0, 1000000) . '_' . $imageName;

                    move_uploaded_file($imageTmp, 'uploaded\images\\' . $image);


                        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND id != ?");
                        $stmt->execute(array($user, $userid));
                        $count = $stmt->rowCount();

                        if($count == 1) {

                            $msgStatus = '<div class="alert-error">Sorry!! This user is already exist</div>';
                            reDirecthome($msgStatus,'back');

                        } else {
                                  // Update the Database with these new info
                                $stmt = $db->prepare("UPDATE users SET username = ?, fullname = ?, email = ?, password = ?, phone = ?, user_image = ?, Group_id = ?, date = ? WHERE id = ?");
                                $stmtCount = $stmt->execute(array($user, $fullname, $mail, $pass, $phone, $image, 0, time(),  $userid));

                                // Echo success message
                                $msgStatus = '<div class="alert-success">User Update</div>';
                                reDirecthome($msgStatus,'back');
                                
                        }

            }

           } else {
            
             $msgStatus ='<div clas="alert-error">Sorry, you can\'t browse this page directly</div>';
             reDirecthome($msgStatus);
           }
           echo '</div>';
           echo '</main>';


        ?>



        <?php  } elseif ($page == 'Delete') {
                echo '<main>';
                echo '<h1 class="heading">Delete!</h1>';
                echo '<div class="container">';

                // Check if th eGet Request userid is numeric & get the integer value of if
                $userid = isset($_GET['userid']) & is_numeric($_GET['userid']) ? $_GET['userid'] : 0;

                $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
                
                $stmt->execute(array($userid));
               
                // The Row count
                $count = $stmt->rowCount();

                // If there is such ID update the database
                if($count > 0) {    

                    $stmt = $db->prepare("DELETE FROM users WHERE id = :zuser ");
                    
                    $stmt->bindParam('zuser', $userid);

                    $stmt->execute();

                    // Echo success message 
                    echo '<div class="alert-success">User deleted</div';

                } else {
                    $msgStatus = '<div class="alert-error">This ID is not exist</div>';
                    reDirecthome($msgStatus, 'back');

                }

                echo '</div>';
                echo '</main>';
         
        
        } elseif ($page == 'View') {
            echo '<main>';
            $userid = isset($_GET['userid']) & is_numeric($_GET['userid']) ? $_GET['userid'] : 0;
 
                 $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
                 $stmt->execute(array($userid));
                 $users = $stmt->fetchAll();

                 ?>
                 
                 <div class='detail-item'>
                 <?php foreach ($users as $user) {?>
                     <div class='detail-container'>
                          <h1 class="heading"><?php echo $user['fullname'];?></h1>
                         <dl>
                             <dd class="dd-img">
                                  <?php if(!empty($user['user_image'])) { ?>
                                 <img class="img-responsive" src="uploaded/images/<?php echo $user['user_image'];?>" alt="">
                                 <?php  } else {
                                     echo '<img class="img-responsive" src="uploaded/images/profile.png" alt="">';
                                 } ?>
                            </dd>
                             <dt>Username</dt>
                             <dd><?php echo $user['username'];?></dd> 
                             <dt>Email</dt>
                             <dd><?php echo $user['email'];?></dd>
                             <dt>Phone</dt>
                             <dd><?php echo '+'. $user['phone'];?></dd>
                             <dt>Joined</dt> 
                             <dd><?php echo $user['date'];?></dd>             
                         </dl>
                     
                     </div>
                     <?php } ?>         
                     <div class='detail-nav'>
                         <button class='see'>
                             <a href="../profile.php">View in websit page</a>
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











