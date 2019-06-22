<?php 
   $password = '';
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php getTitle(); ?></title>
    <link rel="stylesheet" href="<?php echo $css;?>bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $css; ?>font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo $css; ?>css_slider.css">
    <link rel="stylesheet" href="<?php echo $css; ?>style.css">
   
</head>
<body>

  



    
