<?php 
ob_start();
    session_start();
    $pageTilte = 'eCommerce';
    include('init.php'); 
    if(isset($_SESSION['user'])) { 

        $getUser = $db->prepare("SELECT * FROM users WHERE username = ?");
        $getUser->execute(array($sessionUser));
        $info = $getUser->fetch();

        
        
        
        
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
        <h2><a href="#!"><?php echo $info['fullname']; ?></a></h2>
        <p>@<?php echo $info['username']; ?></p>
        </div>
    </div>
    </div>
</div>
</div>
<main>
    <div class="container">
            <div class="panel-header">
                Hello
            </div>
    </div>
</main>

<?php  
    } else {
        header('location: login.php');
        exit();
    }

include $tpl . 'footer.php'; 
ob_end_flush();
?>