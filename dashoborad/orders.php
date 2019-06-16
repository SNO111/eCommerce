<?php
    session_start();
    if($_SESSION['username']) {
        include('init.php');
        $pageTitle = 'Items';


        $page = isset($_GET['page']) ? $_GET['page'] : 'Manage';

        if ($page == 'Manage') { // Manage Page
            echo '<main>';

            $queries = getInnerQuery2('orders.*, users.*, items.*', 'orders', 'users', 'orders', 'customer_id', 'users', 'id', 'items', 'orders', 'order_item_id', 'items', 'id',  'orders', 'id');

        ?>
                <h1 class="heading">Orders</h1>
                <div class="container">
            <div class="quiz-window text-center">
                <div class="quiz-window-header">
                    <div class="quiz-window-title">FULL LIST OF ORDERS</div>
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
                    <?php 
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

    <?php } elseif ($page == 'View') {
        echo '<main>';
        $orderid = isset($_GET['orderid']) & is_numeric($_GET['orderid']) ? $_GET['orderid'] : 0;

                $stmt = $db->prepare("SELECT orders.*, users.*, items.*
                                        FROM orders
                                        INNER JOIN users
                                        ON
                                        orders.customer_id = users.id
                                        INNER JOIN items
                                        ON
                                        comments.order_item_id = items.id
                                        WHERE orders.id = ? 
                                        ORDER BY orders.id DESC");
                $stmt->execute($orderid);
                $orders = $stmt->fetchAll();

                ?>
                
                <div class='detail-item'>
                <?php foreach ($orders as $order) {?>
                    <div class='detail-container'>
                        <h1 class="heading"><?php echo $user['fullname'];?></h1>
                        <dl>
                            <dd class="dd-img">
                                <?php if(!empty($order['item_image'])) { ?>
                                <img class="img-responsive" src="uploaded/images/<?php echo $order['item_image'];?>" alt="">
                                <?php  } else {
                                    echo '<img class="img-responsive" src="uploaded/images/profile.png" alt="">';
                                } ?>
                        </dd>
                            <dt>Name </dt>
                            <dd><?php echo $order['name'];?></dd>
                            <dt>Description</dt>
                            <dd><?php echo $order['order_item_id'];?></dd> 
                            <dt>Price</dt>
                            <dd><?php echo $order['order_item_id'];?></dd>
                            <dt>Date</dt> 
                            <dd><?php echo $order['order_time'];?></dd>             
                        </dl>
                    
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











