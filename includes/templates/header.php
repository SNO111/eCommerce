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
    <div class="upper-nav text-right">
        <div class="container">
        <?php if(isset($_SESSION['user'])) { ?>
            <div class="upper-info">
            <?php echo $_SESSION['user'] . ' -';?> 
                <a  href="profile.php?">Profile</a>
                <a href="logout.php">Logout</a>
            </div>
        <?php } else {
              echo '<div class="navigation-right float-right">';
              echo '<button class="login-btn"><a href="login.php">Login</a></button>';
              echo '</div>';
        }?>
        </div>

    </div>
    <nav class="navbar navbar-expand-sm navbar-light bg-light  justify-content-start">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="layout/images/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse " id="navbarSupportedContent">
                <ul class="navbar-nav text-center">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Categories</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <?php  $queries = getallfrom('*', 'categories',  'WHERE parent = 0', '', 'id', '25');
                            foreach ($queries as $query) {?>    
                            <a class="dropdown-item" href="category.php?pageid=<?php echo $query['id'] . '&pagename='. str_replace(' ', '-', $query['name']);?>" class="nav-link"><?php echo $query['name'];?></a>
                            <?php  } ?> 
                            <a href="categories.php" class="dropdown-item">See All</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    
