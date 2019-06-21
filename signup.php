<?php ob_start();
    session_start();
    $pageTilte = 'Login / Sign up';
    include('init.php'); 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

         // Get variables sfrom the form
         $user           = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
         $fullname       = filter_var($_POST['fullname'], FILTER_SANITIZE_STRING);
         $mail           = filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL);
         $pass           = $_POST['password'];
         $hashedPass     = sha1($pass);


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
         foreach ($formErrors as $error) {
             $msgStatus = '<div class="alert alert-danger">' . $error . '</div>';
             reDirecthome($msgStatus,'back');
         }

         
         // Update the Database with these new info
         if (empty($formErrors)) {

                 $stmt = $db->prepare("INSERT INTO  users (username, fullname, email, password,  Group_id, date) 
                                       VALUES (:zuser, :zfullname, :zemail, :zpass,  1, now())");
                 $stmtCount = $stmt->execute(array(
                     'zuser'     => $user,
                     'zfullname' => $fullname,
                     'zemail'    => $mail,
                     'zpass'     => $hashedPass,
                 ));
                 if ($stmt->rowCount() > 1 ) {
                     echo 'This username is exists aleardy!';
                 } else {
                    // Echo success message
                    if ($stmtCount) {
                        echo '<div class="sign-up-msg text-center">';
                        echo '<div class="alert alert-success">You have Sign Up successfully</div>';
                        echo '<br/>';
                        $msgStatus = '<div class="alert alert-info">You can Login now freely</div>';
                        reDirecthome($msgStatus);
                         echo '</div>';
                         
                    } else {
                        echo '<div class="alert-error">Something went wrong!</div>';
                    }
                }
          }


     } else {
         $msgStatus = '<div class="alert-error">Sorry, you can\'t browse this page directly</div>';
         reDirecthome($msgStatus);
     }




    include $tpl . 'footer.php'; 

    ob_end_flush();
 
 ?>

