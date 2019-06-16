<?php 
    session_start();
    $noNavbar = '';
    $pageTitle = 'Login / Admin Panel';

    // If there is a session redirect to dashborad page 
    if(isset($_SESSION['username'])) { 
        header('Location: dashborad.php'); // Redirect to Dashborad 
        exit();
    }

    include('init.php');
    

    //Check if the user coming from HTTP Request 
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username   = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password   = filter_var($_POST['password'], );
        $hashedPass = sha1($password);


        // Check if the user exsit in database
        $stmt  = $db->prepare("SELECT id, username, password FROM users WHERE username = ? AND password = ? AND Group_id = 1 LIMIT 1");
        $stmt->execute(array($username, $hashedPass));
        $row   = $stmt->fetch();
        $count = $stmt->rowCount();

        // If Count > 0 That mean there is a record about this username
        if ($count > 0 ) {
            $_SESSION['username'] = $username; // Register session Name
            $_SESSION['userid']   = $row['id']; // Register session Name

            header('Location: dashborad.php'); // Redirect to Dashborad 
            exit();
        }

    }
     ?>
     <div class="wrapper">
        <div class="login-container">
            <h1>Welcome</h1>
            <form class="form login" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
                <input type="text" name="username" placeholder="Username">
                <input type="password" name="password" placeholder="Password">
                <button type="submit" id="login-button">Login</button>
            </form>
        </div>
	
        <ul class="bg-bubbles">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>

 <?php


    include $tpl . 'footer.php'; 
   
   ?>


