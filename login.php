<?php ob_start();
    session_start();
    $pageTilte = 'Login / Sign up';
    // If there is a session redirect to dashborad page 
    if(isset($_SESSION['user'])) { 
        header('location: index.php'); // Redirect to Dashborad 
        exit();
    }
    include('init.php');

    echo '<main class="login-page">
            <div class="wrapper">
                <div class="login-container">';

    //Check if the user coming from HTTP Request 
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username   = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password   = $_POST['password'];
        $hashedPass  = sha1($password);
          

        // Check if the user exsit in database
        $stmt = $db->prepare("SELECT username, password FROM users WHERE username = ? AND password = ?");
        $stmt->execute(array($username, $hashedPass));
        $row   = $stmt->fetch();
        $count = $stmt->rowCount();
        // If Count > 0 That mean there is a record about this username
        if ($count > 0 ) {
            $_SESSION['user'] = $username; // Register session Name
            header('Location: index.php'); // Redirect to Dashborad 
            exit();
        } 
    }?>
                <h1 class="text-center">
                    <span class="actived" data-class="login">Welcome</span>  /
                     <span data-class="signup">SIGN UP</span>
                </h1>
                <div class="login">
                    <form class="form login" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
                        <input type="text" name="username" placeholder="Username" required autocomplete="off">
                        <input type="password" name="password" placeholder="Password" autocomplete="new-password">
                        <button type="submit" id="login-button">Login</button>
                    </form>
                </div>
                <div class="signup">
                    <form class="form login" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
                        <input type="text" name="username" placeholder="Username" autocomplete="off">

                        <input type="password" name="password" placeholder="Password" autocomplete="new-password">
                        <button type="submit" id="signup-button">Sign Up</button>
                    </form>
                </div>
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
     </main>

 <?php 
    include $tpl . 'footer.php'; 

    ob_end_flush();
 
 ?>

