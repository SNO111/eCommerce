<?php 
ob_start();
    session_start();
    $pageTilte = 'eCommerce';
    include('init.php'); 

    if(isset($_SESSION['user'])) { 

        

        $getUser = $db->prepare("SELECT * FROM users WHERE username = ?");
        $getUser->execute(array($sessionUser));
        $info = $getUser->fetch();


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
            $phone = filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT);
            $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);

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
             if(empty($phone)) {
                $formErrors[] = 'Insert your Phone number';
            }
            if(empty($address)) {
                $formErrors[] = 'Insert your Address';
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
             foreach ($formErrors as $error) { ?>
                <div class="container">
                    <div class="row">
                        <div class="col-md-8" style="margin: 0 auto;">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><?php echo $error;?></strong> 
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        </div>
                    </div>
                </div>
             <?php }

             if (empty($formErrors)) {
                $image =rand(0, 1000000) . '_' . $imageName;
                if(!($image) == "") {
                    move_uploaded_file($imageTmp, 'dashoborad/uploaded\images\\' . $image);
                }

                $stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND id != ?");
                $stmt->execute(array($user, $userid));
                $count = $stmt->rowCount();

                if($count == 1) {

                    $msgStatus = '<div class="alert alert-danger">Sorry!! This user is already exist</div>';

                } else {
                        // Update the Database with these new info
                        $stmt = $db->prepare("UPDATE users SET username = ?, fullname = ?, email = ?, password = ?, phone = ?, user_image = ?, address = ?, Group_id = ?, date = ? WHERE id = ?");
                        $stmtCount = $stmt->execute(array($user, $fullname, $mail, $pass, $phone, $image, $address, 0, time(),  $userid));

                        // Echo success message
                        ?>
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-8" style="margin: 0 auto;">
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Profile Updated</strong> 
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            
                        
                    <?php header('location: profile.php');
                            exit();
                }

             }

        } 

        $page = isset($_GET['page']) ? $_GET['page'] : 'Profile';
        if ($page == 'Delete') {
        // Delete user Account forever--------- 
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
            echo '<div class="container">';
            echo '<div class="alert alert-success">User deleted</div';
            echo '</div>';
            header('location: logout.php');
            exit();

        } 
    } 
        ?>
    <div class="profile-header">
  
    <div class="profile-grup">
    <div class="cover-profile">
    <div class="image-paralax"></div>
        <div class="profile">
        <div class="profile-image">
            <?php if(!empty($info['user_image'])) { ?>
                <img class="img-fluid" src="dashoborad/uploaded/images/<?php echo $info['user_image'];?>" alt="">
            <?php  } else {
                echo '<img class="img-responsive" src="dashoborad/uploaded/images/profile.png" alt="">';
            } ?>
        </div>
        <div class="profile-data">
        <h2><a href="profile.php?userid=<?php echo $info['id']; ?>"><?php echo $info['fullname']; ?></a></h2>
        <p>@<?php echo $info['username']; ?></p>
        </div>
    </div>
    </div>
</div>
</div>
<main>

<div class="container">
    <div class="row">
       <div class="col-md-8 user-profile-info">
            <div class="user-profile-details">
            <p>
                <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#collapseDetails" aria-expanded="false" aria-controls="collapseExample">
                    <i class="fa fa-user"></i>
                </button>
                <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#collapseSettings" aria-expanded="false" aria-controls="collapseExample">
                    <i class="fa fa-cog"></i>
                </button>
                <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#collapseOrders" aria-expanded="false" aria-controls="collapseExample">
                    <i class="fa fa-shopping-cart"></i>
                </button>
                <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#collapseMails" aria-expanded="false" aria-controls="collapseExample">
                    <i class="fa fa-envelope"></i>
                </button>
            </p>
            <div class="collapse" id="collapseDetails">
                <div class="card card-body">
                    <h5 class="text-center">Profile Information</h5>
                    <ul class="list-unstyled">
                        <li class="nav-collapse">
                            <span>Full name:</span><h5><?php echo $info['fullname'];?></h5>
                        </li>
                        <hr>
                        <li class="nav-collapse">
                            <span>Email:</span><h5><?php echo $info['email'];?></h5>
                        </li>
                        <hr>
                        <li class="nav-collapse">
                            <span>Phone Number:</span><h5>+<?php echo $info['phone'];?></h5>
                        </li>
                        <hr>
                        <li class="nav-collapse">
                            <span>Address:</span><h5><?php if(!empty($info['address'])) {echo $info['address']; } else { echo '<h6>No <strong>address</strong> added yet</h6>'; }?></h5>
                        </li>
                        <hr>
                    </ul>
                </div>
            </div>
            <div class="collapse" id="collapseSettings">
                <div class="card card-body">
                    <h5 class="text-center">Edit your profile</h5>
                    <form class="form-style-7" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" enctype="multipart/form-data">
                        <ul>
                            <input type="hidden" name="userid" value="<?php echo $info['id'];?>">
                            <li>
                                <label for="username">Username</label>
                                <input type="text" name="username" maxlength="100" value="<?php echo $info['username'];?>" required="required">
                                <span>Username</span>
                            </li>
                            <li>
                                <label for="fullname">Full Name</label>
                                <input type="text" name="fullname" maxlength="100" value="<?php echo $info['fullname'];?>" required="required">
                                <span>Full Name</span>
                            </li>
                            <li>
                                <label for="email">E-mail</label>
                                <input type="email" name="mail" maxlength="100" value="<?php echo $info['email'];?>" required="required">
                                <span>E-mail</span>
                            </li>
                            <li>
                                <label for="password">Password</label>
                                <input type="hidden" name="oldPassword" maxlength="100" value="<?php echo $info['password'];?>">
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
                                <input type="text" name="phone" maxlength="100" value="<?php echo $info['phone'];?>" required="required">
                                <span>Enter your Phone Number to receive a SMS after signup</span>
                            </li>
                            <li>
                                <label for="address">Address</label>
                                <input type="text" name="address" maxlength="100" value="<?php echo $info['address'];?>" required="required">
                                <span>Enter your address</span>
                            </li>
                            <li>
                                <label for="image">Image</label>
                                <input type="file" name="image">
                                <br> <br>
                                <span>Please choose an image</span>
                            </li>
                            <li>
                                <input onClick="document.location.reload(true)" class="btn btn-info" type="submit" value="Edit Profile" >
                            </li>
                        </ul>
                    </form>
                    <hr>
                    <a id="alert" class="btn btn-danger confirm" href="?page=Delete&userid=<?php echo $info['id'];?>">
                        <i class="fa fa-close"></i>
                        <span class="delete">Delete</span>
                    </a>               
                 </div>
            </div>
            <div class="collapse" id="collapseOrders">
                <div class="card card-body">
                    <span class="collapse-headding">ORDER:</span> 
                    <p>No order yet</p>

            </div>
            </div>
            <div class="collapse" id="collapseMails">
                <div class="card card-body">
                    <span class="collapse-headding">MAIL:</span> 
                    <p>No mail inbox</p>

            </div>
            </div>
            
            
            </div>
       
       </div>
    </div>
</div>
</main>

<?php  
    } else {
        header('location: index.php');
        exit();
    }

include $tpl . 'footer.php'; 
ob_end_flush();
?>