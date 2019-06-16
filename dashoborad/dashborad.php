<?php 
    session_start();
    if(isset($_SESSION['username'])) { 
        $pageTilte = 'Dashborad';
        include('init.php');
        
        ?>
    <main class="dashborad">
        <div class="container">
            <h2 class="heading">overview</h2>
                <div class="overview">
                    <div class="overview-item text-center over-item-1">
                        <div class="icon">
                            <i class="fa fa-users"></i>
                        </div>
                        <div class="text">
                            <h2><?php echo rowCount('id', 'users');?></h2>
                            <span>Members</span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="overview-item text-center over-item-2">
                        <div class="icon">
                            <i class="fa fa-shopping-basket"></i>
                        </div>
                        <div class="text">
                            <h2><?php echo rowCount('id', 'items');?></h2>
                            <span>Items</span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="overview-item text-center over-item-3">
                        <div class="icon">
                            <i class="fa fa-shopping-cart"></i>
                        </div>
                        <div class="text">
                            <h2><?php echo rowCount('id', 'orders');?></h2>
                            <span>Orders</span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="overview-item text-center over-item-4">
                        <div class="icon">
                            <i class="fa fa-comments"></i>
                        </div>
                        <div class="text">
                            <h2><?php echo rowCount('id', 'comments');?></h2>
                            <span>Comments</span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
        


                </div>
                <div class="clearfix"></div>
        </div>
        <div class="container">

            <div class="latest">
                <h2 class="heading text-center">Latest joined users</h2>
                <div class="latest-item-list latest-users">
                    <ul>
                        <?php  $queries = getAllQuery('*', 'users', 'id');
                            foreach ($queries as $query) {
                        ?>
                        <li class="latest-item">
                        <span class="date"><?php echo $query['date'];?></span>

                            <div class="userimg">
                                <?php if(!empty($query['user_image'])) { ?>
                                        <img class="user-profile img-responsive" src="uploaded/images/<?php echo $query['user_image'];?>" alt="">
                                <?php  } else {
                                        echo '<img class="user-profile img-responsive" src="uploaded/images/profile.png" alt="">';
                                    } 
                                ?>
                            </div>
                            <div class="userinfo">
                                <h3>
                                    <?php echo $query['fullname'];?>
                                </h3>
                                <p><a href="customers.php?page=View&userid=<?php echo $query['id'];?>"><?php echo $query['username'];?></a></p>
                            </div>

                            <div class="clearfix"></div>
                            <div class="latest-btn">
                                <a class="button button-green" href="customers.php?page=View&userid=<?php echo $query['id'];?>">
                                    <i class="fa fa-eye"></i>
                                    <span class="view">view</span>
                                </a>   
                            </div>
                        </li>
                            <?php } ?>
                            <div class="clearfix"></div>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Latest Users -->

        <div class="container">

            <div class="latest">
                <h2 class="heading text-center">Latest Cooment</h2>
                <div class="latest-item-list latest-comments">
                    <ul>
                        <?php  $queries = getInnerQuery('comments.*, users.username, users.user_image', 'comments', 'users', 'comments', 'user_id', 'users', 'id', 'id');
                            foreach ($queries as $query) {
                        ?>
                        <li class="latest-item text-center">

                            <div class="user-com-img">
                                <?php if(!empty($query['user_image'])) { ?>
                                        <img class="user-profile img-responsive" src="uploaded/images/<?php echo $query['user_image'];?>" alt="">
                                <?php  } else {
                                        echo '<img class="user-profile img-responsive" src="uploaded/images/profile.png" alt="">';
                                    } 
                                ?>
                            </div>
                            <h4>
                                <a href="customers.php?page=View&userid=<?php echo $query['id'];?>"><?php echo $query['username'];?></a>
                            </h4>
                            <p class="comments">
                                <?php $text = substr($query['comment'], 0, 90);
                                        echo $text .
                                        '<br /><a class="see-more" href="?page=View&comid=' . 
                                        $query['id'] . 
                                        '">See More...</a>';
                                ?>
                            </p>

                            <div class="clearfix"></div>
                        </li>
                            <?php } ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- End Latest Comments -->
        <div class="clearfix"></div>



        <div class="container">
            <h2 class="heading">Latest messages</h2>
            <section class="section-wrap">
                <!--for demo wrap-->

                <div class="tbl-header">
                    <table cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <td>Subject:</td>
                            <th class="contact-msg">Message:</th>
                            <th>By Username:</th>
                            <th>Date:</th>
                        </tr>
                    </thead>
                    </table>
                </div>
                <div class="tbl-content">
                    <table cellpadding="0" cellspacing="0">
                    <?php 
                        $queries = getAllQuery('*', 'contact', 'id');
                        foreach ($queries as $query) { ?>
                        <tbody>
                            <tr>
                            <td><?php echo $query['subject'];?></td>
                            <td class="contact-msg"><?php echo $query['message'];?></td>
                            <td><?php echo $query['name'];?></td>
                            <td><?php echo $query['time'];?></td>
                            </tr>
                        </tbody>
                        <?php } ?>
                    </table>
                </div>
            </section>
        </div>
        <div class="clearfix"></div>
        <!-- End Contact table -->


        <div class="container">
            <div class="quiz-window text-center">
                <div class="quiz-window-header">
                    <div class="quiz-window-title">ORDERS</div>
                    <button id="toggle-click" class="quiz-window-close"><i class="fa fa-angle-down"></i><i class="fa fa-angle-up"></i></button>
                </div>
                <div class="quiz-window-body">
                    <div class="gui-window-awards">
                    <ul class="guiz-awards-row guiz-awards-header">
                        <li class="guiz-awards-header-star">Order</li>
                        <li class="guiz-awards-header-title">Item</li>
                        <li class="guiz-awards-header-track">Price</li>
                        <li class="guiz-awards-header-time">Time</li>
                    </ul>
                    <?php $queries = getInnerQuery2('orders.*, users.* ,items.*', 'orders', 'users', 'orders', 'customer_id', 'users', 'id', 'items', 'orders', 'order_item_id', 'items', 'id',  'orders', 'id');

                        foreach ($queries as $query) {?>
                    <ul class="guiz-awards-row guiz-awards-row-even">
                        <li class="guiz-awards-star"><!--<span class="star goldstar"></span>-->
                            <?php if(!empty($query['user_image'])) { ?>
                                <img class="star  goldstar img-responsive" src="uploaded/images/<?php echo $query['user_image'];?>" alt="">
                            <?php  } else {
                                echo '<img class="star  goldstar img-responsive" src="uploaded/images/profile.png" alt="">';
                             } ?>
                             
                        
                        </li>
                        <li class="guiz-awards-title"><?php echo $query['name'];?>
                        <div class="guiz-awards-subtitle">
                            <?php
                                $stmt = $db->prepare("SELECT items.*, categories.*
                                                        FROM items
                                                        INNER JOIN categories
                                                        ON
                                                        items.cat_item_id = categories.id
                                                        WHERE items.id = ?");
                                $stmt->execute(array($query['id']));
                                $coms = $stmt->fetchAll();
                                foreach ($coms as $cat) {
                                    echo $cat['name'];
                                }
                            ?>
                        </div>
                        </li>
                        <li class="guiz-awards-price"><?php echo '$' . $query['price'];?></li>
                        <li class="guiz-awards-time"><?php echo $query['order_time'];?></li>
                    </ul>
                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <!--  End Orders table -->

    </main>




<?php


        



































        include $tpl . 'footer.php';

    } else {

        header('Location: index.php');
        exit();

    }
