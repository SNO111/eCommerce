
<nav class="navbar navbar-default navbar-expand-lg navbar-light navbar-fixed-top">
    <div class="container">
        <div class="navbar-header d-flex col">
            <a class="navbar-brand" href="index.php">Show<b>Now</b></a>  		
            <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle navbar-toggler ml-auto">
                <span class="navbar-toggler-icon"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <!-- Collection of nav links, forms, and other content for toggling -->
        <div id="navbarCollapse" class="collapse navbar-collapse justify-content-start">
            <ul class="nav navbar-nav">		
                <li class="nav-item dropdown">
                    <a data-toggle="dropdown" class="nav-link dropdown-toggle" href="#">Categories <b class="caret"></b></a>
                    <ul class="dropdown-menu">					
                        <?php  $queries = getallfrom('*', 'categories',  'WHERE parent = 0', '', 'id', '25');
                            foreach ($queries as $query) {?>    
                            <a class="dropdown-item" href="category.php?pageid=<?php  echo $query['id'] . '&pagename='. str_replace(' ', '-', $query['name']);?>" class="nav-link"><?php  echo $query['name'];?></a>
                            <?php   } ?> 
                        <a href="categories.php" class="dropdown-item">See All</a>

                    </ul>
                </li>
                <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
                <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>	
            </ul>
            <form class="navbar-form form-inline">
                <div class="input-group search-box">								
                    <input type="text" id="search" class="form-control" placeholder="Search here...">
                    <span class="input-group-addon"><i class="fa fa-search">&#xE8B6;</i></span>
                </div>
            </form>
            
            <?php if(isset($_SESSION['user'])) { ?>
                    <ul class="nav navbar-nav navbar-right ml-auto">			
                        <li class="nav-item">
                            <a data-toggle="dropdown" class="btn dropdown-toggle get-started-btn mt-1 mb-1" href="#"><?php echo $_SESSION['user'];?> </a>
                            <ul class="dropdown-menu form-wrapper dropdown-user">					
                                <li>
                                   <a  href="profile.php">Profile</a>
                                </li>
                                <li>
                                    <a href="logout.php">Logout</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link dropdown-toggle" href="cart.php">
                                <i class="fa fa-shopping-cart"></i>
                            </a>
                        </li>	
                </ul>
            <?php } else { ?>
            <ul class="nav navbar-nav navbar-right ml-auto">
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" href="cart.php">
                         <i class="fa fa-shopping-cart"></i>
                    </a>
                </li>			
                <li class="nav-item">
                    <a data-toggle="dropdown" class="nav-link dropdown-toggle" href="#">Login</a>
                    <ul class="dropdown-menu form-wrapper">					
                        <li>
                            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
                                <p class="hint-text"></p>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="username" placeholder="Username" required="required">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" placeholder="Password" required="required" autocomplete="new-password">
                                </div>
                                <input type="submit" class="btn btn-primary btn-block" value="Login">
                                <div class="form-footer">
                                    <a href="#">Forgot Your password?</a>
                                </div>
                            </form>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" data-toggle="dropdown" class="btn dropdown-toggle get-started-btn mt-1 mb-1">Sign up</a>
                    <ul class="dropdown-menu form-wrapper">					
                        <li>
                            <form action="signup.php" method="POST">
                                <p class="hint-text">Fill in this form to create your account!</p>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="username" placeholder="Username" required="required">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="fullname" placeholder="Your Full Name" required="required">
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="mail" placeholder="Email" required="required">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" placeholder="Password" required="required" autocomplete="new-password">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="confirm_password" placeholder="confirm Password" required="required">
                                </div>
                                <input type="submit" class="btn btn-primary btn-block" value="Sign up">
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
            <?php } ?>
        </div>
    </div>
</nav>
