<nav class="menu" tabindex="0">
	<div class="smartphone-menu-trigger"></div>
  <header class="avatar">
<?php $users = getAllFrom("*", "users", "WHERE id = {$_SESSION['userid']}", "", "id");
          foreach ($users as $admin) {

              if(!empty($admin['user_image'])) { ?>

              <img src="uploaded/images/<?php echo $admin['user_image'];?>" alt="">

          <?php  } else {

              echo '<img src="uploaded/images/profile.png" alt="">';

              } 

          } ?>

    <h2><?php echo $_SESSION['username'];?>.</h2>
  </header>
	<ul>
    <li tabindex="0" class="icon-homeSite"><a href="../index.php"><span>View Site</span></a></li>
    <li tabindex="0" class="icon-dashboard"><a href="dashborad.php"><span>Dashborad</span></a></li>
    <li tabindex="0" class="icon-users"><a href="customers.php"><span>Customers</span></a></li>
    <li tabindex="0" class="icon-categories"><a href="categories.php"><span>Categories</span></a></li>
    <li tabindex="0" class="icon-items"><a href="items.php"><span>Items</span></a></li>
    <li tabindex="0" class="icon-comments"><a href="comments.php"><span>Comments</span></a></li>
    <li tabindex="0" class="icon-shopping-cart"><a href="orders.php"><span>Orders</span></a></li>
  </ul>
    <ul class="settings">
      <li tabindex="0" class="icon-settings"><a href="#">Settings</a>
      <ul>
          <li><span><a href="logout.php">Logout</a></span></li>
      </ul>
    </li>
  </ul>
</nav>