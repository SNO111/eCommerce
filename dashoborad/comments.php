<?php
    session_start();
    if($_SESSION['username']) {
        include('init.php');
        $pageTitle = 'Items';


        $page = isset($_GET['page']) ? $_GET['page'] : 'Manage';

        if ($page == 'Manage') { // Manage Page
            echo '<main>';

            $stmt = $db->prepare("SELECT comments.*, users.username, users.user_image, items.name AS item_name
                                  FROM comments
                                  INNER JOIN users
                                  ON
                                  comments.user_id = users.id
                                  INNER JOIN items
                                  ON
                                  comments.item_id = items.id 
                                  ORDER BY comments.id DESC");
            $stmt->execute();
            $coms = $stmt->fetchAll();

        ?>
                <h1 class="heading">Comments</h1>
                <div class="container">
                    <a class="btn-add" href="?page=Add"><i class="fa fa-plus"></i>Add Comment</a>
                    <section class="section-wrap">
                        <!--for demo wrap-->

                        <div class="tbl-header">
                            <table cellpadding="0" cellspacing="0">
                            <thead>
                                <tr>
                                <th>Comment:</th>
                                <th>By Username:</th>
                                <th>On Date:</th>
                                <th>Item Name:</th>
                                <th>Manage</th>
                                </tr>
                            </thead>
                            </table>
                        </div>
                        <div class="tbl-content">
                            <table cellpadding="0" cellspacing="0">
                            <?php foreach ($coms as $com) { ?>
                            <tbody>
                                <tr>
                                <td>
                                    <?php $text = substr($com['comment'], 0, 120);
                                            echo $text .
                                            '<br /><a class="see-more" href="?page=View&comid=' . 
                                            $com['id'] . 
                                            '">See More...</a>';
                                    ?>
                                </td>
                                <td class="com-user-img"><?php 
                                         if(!empty($com['user_image'])) { ?>
                                        <img class="user-profile img-responsive" src="uploaded/images/<?php echo $com['user_image'];?>" alt="">
                                        <?php  } else {
                                            echo '<img class="user-profile img-responsive" src="uploaded/images/profile.png" alt="">';
                                            } 
                                            echo '<span>' . $com['username'] . '</span>'; 
                                        
                                    ?>
                                </td>
                                <td><?php echo $com['comment_date'];?></td>
                                <td><?php echo $com['item_name'];?></td>
                                <td>
                                    <a class="button button-blue" href="?page=Edit&comid=<?php echo $com['id'];?>">
                                        <i class="fa fa-globe"></i>
                                        <span class="edit">Edit</span>
                                    </a>
                                    <a id="alert" class="button button-red confirm" href="?page=Delete&comid=<?php echo $com['id'];?>">
                                        <i class="fa fa-close"></i>
                                        <span class="delete">Delete</span>
                                    </a>
                                    <!--  Select View Button to show Comment info -->
                                    <a class="button button-green" href="?page=View&comid=<?php echo $com['id'];?>">
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
                <h1 class="heading">add Cooment</h1>
                <div class="container">
                    <form class="form-style-7" action="?page=Insert" method="POST" enctype="multipart/form-data">
                        <ul>
                            <li>
                                <label for="comment">Message</label>
                               <textarea name="comment" cols="6"></textarea>
                                <span>Enter your message here</span>
                            </li>
                            <li>
                                <label for="confirm_password">For Users</label>
                                 <select name="user" id="">
                                     <option value="0">--</option>
                                     <?php 
                                     $stmt = $db->prepare("SELECT id, username FROM users");
                                     $stmt->execute();
                                     $usersNames = $stmt->fetchAll();
                                     foreach($usersNames as $user) { ?>
                                    <option value="<?php echo $user['id'];?>"><?php echo $user['username'];?></option>
                                    <?php } ?>
                                 </select>
                                <span>Enter your Repeat Password please</span>
                            </li>
                            <li>
                                <label for="items">On Items</label>
                                <select name="item" id="">
                                     <option value="0">--</option>
                                     <?php 
                                     $stmt = $db->prepare("SELECT id, name FROM items");
                                     $stmt->execute();
                                     $itemsNames = $stmt->fetchAll();
                                     foreach($itemsNames as $item) { ?>
                                    <option value="<?php echo $item['id'];?>"><?php echo $item['name'];?></option>
                                    <?php } ?>
                                 </select>                                
                                 <span>Enter your Phone Number to receive a SMS after signup</span>
                            </li>
                            <li>
                                <input class="btn-add" type="submit" value="Add Comment" >
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

                    // Get variables sfrom the form
                    $com            = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                    $user           = $_POST['user'];
                    $item           = $_POST['item'];


                    $formErrors = array();
                    //  Check if there is no error proceed the insert operation
                    if(empty($com)) {
                        $formErrors[] = 'Comment Field can\'t be empty';
                    }
                    if (empty($user)) {
                        $formErrors[] = 'User Field can\'t be empty';
                    }
                    if (empty($item)) {
                        $formErrors[] = 'Item Field can\'t be empty';
                    }
                    foreach ($formErrors as $error) {
                        $msgStatus = '<div class="alert-error">' . $error . '</div>';
                        reDirecthome($msgStatus,'back', 5);
                    }

                    
                    // Update the Database with these new info
                    if (empty($formErrors)) {

                            $stmt = $db->prepare("INSERT INTO  comments (comment, comment_date, user_id, item_id) 
                                                  VALUES (:zcom, now(), :zuserid, :zitemid)");
                            $stmtCount = $stmt->execute(array(
                                'zcom'        => $com,
                                'zuserid'     => $user,
                                'zitemid'     => $item
                            ));

                            // Echo success message
                            if ($stmtCount) {
                                $msgStatus = '<div class="alert-success">Comment Added</div>';
                                reDirecthome($msgStatus, 'back');
                            } else {
                                $msgStatus = '<div class="alert-error">Something went wrong!</div>';
                                reDirecthome($msgStatus);
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
            $comid = isset($_GET['comid']) & is_numeric($_GET['comid']) ? $_GET['comid'] : 0;
            $stmt = $db->prepare("SELECT * FROM comments WHERE id = ?");
            $stmt->execute(array($comid));
            $com = $stmt->fetch();

        ?>
        
            <h1 class="heading">Edit Comment</h1>
            <div class="container">
                <form class="form-style-7" action="?page=Update" method="POST">
                    <ul>
                        <input name="comid" type="hidden" vlaue=" <?php echo $comid;?>">
                        <li>
                            <label for="comment">Comment</label>
                            <textarea name="comment"><?php echo $com['comment'];?></textarea>
                            <span>Edit your message</span>
                        </li>
                        <li>
                            <label for="username">Select Username</label>
                            <select name="user">
                                <option value="0">...</option>
                                <?php 
                                $stmt = $db->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach ($users as $user) {
                                   echo '<option vlaue="' . $user['id'] . '"';
                                   if ($com['user_id'] == $user['id']) {
                                       echo 'selected';
                                   }
                                   echo '>' . $user['username'] . '</option>';
                                }
                                ?>
                            </select>
                            <span>Edit User</span>
                        </li>
                        <li>
                            <label for="item">Select Item</label>
                            <select name="item">
                                <option value="0">...</option>
                                <?php 
                                $stmt = $db->prepare("SELECT * FROM items");
                                $stmt->execute();
                                $items = $stmt->fetchAll();
                                foreach ($items as $item) {
                                   echo '<option vlaue="' . $item['id'] . '"';
                                   if ($com['item_id'] == $item['id']) {
                                       echo 'selected';
                                   }
                                   echo '>' . $item['name'] . '</option>';
                                }
                                ?>
                            </select>
                            <span>Edit Item</span>

                        </li>
                        <li>
                            <input class="btn-add" type="submit" value="Save" >
                        </li>
                    </ul>
                </form>
            </div>
        </main>


       <?php } elseif ($page == 'Update') {
           echo '<main>';
           echo '<h1 class="heading">Update!</h1>';
           echo '<div class="container">';
           if ($_SERVER['REQUEST_METHOD'] == 'POST') {

               // Get variables sfrom the form
               $id          = $_POST['comid'];
               $com         = $_POST['comment'];
               $user        = $_POST['user'];
               $item        = $_POST['item'];

                //  Check if there is no error proceed the insert operation
                $formErrors = array();
                if(empty($com)) {
                    $formErrors[] = 'Leave A comment First!';
                }
                if($user === 0) {
                    $formErrors[] = 'Select user pleace!';
                }
                if($item === 0) {
                    $formErrors[] = 'Select Item pleace!';
                }
                foreach ($formErrors as $error) {
                    echo '<div class="alert-error">' . $error . '</div>';
                }

                if (empty($formErrors)) {

                    // Update the Database with these new info
                    $stmt = $db->prepare("UPDATE comments SET comment = ?, user_id = ?, Item_id = ? WHERE id = ?");
                    $stmtCount = $stmt->execute(array($com, $user, $item, $id));

                    // Echo success message
                    if ($stmtCount) {
                        $msgStatus = '<div class="alert-success">Comment updated</div>';
                        reDirecthome($msgStatus, 'back');
                    } else {
                        $msgStatus = '<div class="alert-error">Something went wrong!</div>';
                        reDirecthome($msgStatus);
                }
            }

           } else {
               $msgStatus = '<div class="alert-error">Sorry, you can\'t browse this page directly</div>';
               reDirecthome($msgStatus);
           }
           echo '</div>';
           echo '</main>';


        ?>


        <?php  } elseif ($page == 'Delete') {
                echo '<main>';
                echo '<h1 class="heading">Delete!</h1>';
                echo '<div class="container">';

                // Check if th eGet Request itemid is numeric & get the integer value of if
                $comid = isset($_GET['comid']) & is_numeric($_GET['comid']) ? $_GET['comid'] : 0;

                $stmt = $db->prepare("SELECT * FROM comments WHERE id = ?");
                
                $stmt->execute(array($comid));
               
                // The Row count
                $count = $stmt->rowCount();

                // If there is such ID update the database
                if($count > 0) {    

                    $stmt = $db->prepare("DELETE FROM comments WHERE id = :zcomment ");
                    
                    $stmt->bindParam('zcomment', $comid);

                    $stmt->execute();

                    // Echo success message 
                    $msgStatus = '<div class="alert-success">Comment deleted</div';
                    reDirecthome($msgStatus, 'back');

                } else {
                    $msgStatus = '<div class="alert-error">This ID is not exist</div>';
                    reDirecthome($msgStatus);
                }

                echo '</div>';
                echo '</main>';
         
        
        } elseif ($page == 'View') {
            echo '<main>';
            $comid = isset($_GET['comid']) & is_numeric($_GET['comid']) ? $_GET['comid'] : 0;
 
                 $stmt = $db->prepare("SELECT comments.*, users.*, items.name AS item_name
                                        FROM comments
                                        INNER JOIN users
                                        ON
                                        comments.user_id = users.id
                                        INNER JOIN items
                                        ON
                                        comments.item_id = items.id 
                                        WHERE comments.id = ?");
                 $stmt->execute(array($comid));
                 $coms = $stmt->fetchAll();

                 ?>
                 <div class='detail-item'>
                 <?php foreach ($coms as $com) {?>
                    
                     <div class='detail-container'>
                        <div class="v-c-user-img">
                            <?php if(!empty($com['user_image'])) { ?>
                            <img class="img-responsive" src="uploaded/images/<?php echo $com['user_image'];?>" alt="">
                            <?php  } else {
                                echo '<img class="img-responsive" src="uploaded/images/profile.png" alt="">';
                            } ?>
                            <a href="customers.php?page=View&userid=<?php echo $com['user_id'];?>">
                                <span class="by">By</span>
                                <?php echo $com['username'];?>
                            </a>
                            <div class="clearfix"></div>
                        </div>
                        <div id="show-com">
                            <p class="show-com"><?php echo $com['comment']; ?></p>
                        </div>
                        <div class="com-info">
                            <span class="date"><?php echo $com['comment_date'];?></span>
                            <a id="alert" class="button button-red com-info-btn" href="?page=Delete&comid=<?php echo $com['id'];?>">
                                <i class="fa fa-close"><span class="delete">Delete</span></i>
                            </a>
                            <div class="clearfix"></div>
                        </div>

                     
                     </div>
                     <?php } ?>         
                     <div class='detail-nav'>
                         <button class='see'>
                             <a href="..//single-item.php">View in websit page</a>
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











