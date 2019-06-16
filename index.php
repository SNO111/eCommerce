<?php 
session_start();
$pageTilte = 'eCommerce';
include('init.php'); ?>
<header>
    <div class="slider-body">
            <!-- Slider Wrapper -->
        <div class="css-slider-wrapper">
        <input type="radio" name="slider" class="slide-radio1" checked id="slider_1">
        <input type="radio" name="slider" class="slide-radio2" id="slider_2">
        <input type="radio" name="slider" class="slide-radio3" id="slider_3">
        <input type="radio" name="slider" class="slide-radio4" id="slider_4">
        
        <!-- Slider Pagination -->
        <div class="slider-pagination">
            <label for="slider_1" class="page1"></label>
            <label for="slider_2" class="page2"></label>
            <label for="slider_3" class="page3"></label>
            <label for="slider_4" class="page4"></label>
        </div>
        
        <!-- Slider #1 -->
        <div class="slider slide-1">
            <img src="layout/images/model-1.png" alt="">
            <div class="slider-content">
            <h4>New Product</h4>
            <h2>Denim Longline T-Shirt Dress With Split</h2>
            <button type="button" class="buy-now-btn" name="button">$130</button>
            </div>
            <div class="number-pagination">
            <span>1</span>
            </div>
        </div>
        
        <!-- Slider #2 -->
        <div class="slider slide-2">
            <img src="layout/images/model-2.png" alt="">
            <div class="slider-content">
            <h4>New Product</h4>
            <h2>Denim Longline T-Shirt Dress With Split</h2>
            <button type="button" class="buy-now-btn" name="button">$130</button>
            </div>
            <div class="number-pagination">
            <span>2</span>
            </div>
        </div>
        
        <!-- Slider #3 -->
        <div class="slider slide-3">
            <img src="layout/images/model-3.png" alt="">
            <div class="slider-content">
            <h4>New Product</h4>
            <h2>Denim Longline T-Shirt Dress With Split</h2>
            <button type="button" class="buy-now-btn" name="button">$130</button>
            </div>
            <div class="number-pagination">
            <span>3</span>
            </div>
        </div>
        
        <!-- Slider #4 -->
        <div class="slider slide-4">
            <img src="layout/images/model-4.png" alt="">
            <div class="slider-content">
            <h4>New Product</h4>
            <h2>Denim Longline T-Shirt Dress With Split</h2>
            <button type="button" class="buy-now-btn" name="button">$130</button>
            </div>
            <div class="number-pagination">
            <span>4</span>
            </div>
        </div>
        </div>
    </div>
</header>



  

<!-- Main Content -->
<main>
    <div class="container-fluid">
        <div class="row">
            <?php 
                $limit =  6;
                $stmt = $db->prepare("SELECT * FROM items  WHERE `name` LIKE '%outfit%' ORDER BY id DESC LIMIT  6");
                $stmt->execute();
                $allItems = $stmt->fetchAll();
                foreach ($allItems as $item) {
               
            ?>
                <div class="col-md-4 col-lg-4">
                    <div class="product-item text-center">
                        <div class="p-item-img">
                            <img class="img-fluid" src="dashoborad/uploaded/images/<?php echo $item['item_image'];?>" alt="">
                        </div>
                        <hr class="separate">
                        <div class="p-item-info">
                            <h4 class="p-title"><?php echo $item['name']; ?></h4>
                            <p class="p-price"><span>$</span><?php echo $item['price'];?></p>
                        </div>
                        <a href="product-page.php?pageid=<?php echo $item['id']; ?>&pagename=<?php echo $item['name']; ?>" class="check-it">BUY NOW</a>

                     </div>
                </div>
             <?php } ?>
        </div>
    </div>

    <!-- ADs -->
    <div class="ads women-fashion">
         <img class="img-fluid" src="layout/images/fashion-20191.jpg" alt="">
    </div>

    <!-- End ADs -->


    <div class="container-fluid">
        <div class="row">
            <?php 
                $limit =  6;
                $stmt = $db->prepare("SELECT * FROM items  WHERE `name` LIKE '%bag%' ORDER BY id DESC LIMIT  6");
                $stmt->execute();
                $allItems = $stmt->fetchAll();
                foreach ($allItems as $item) {
               
            ?>
                <div class="col-md-4 col-lg-4">
                    <div class="product-item text-center">
                        <div class="p-item-img">
                            <img class="img-fluid" src="dashoborad/uploaded/images/<?php echo $item['item_image'];?>" alt="">
                        </div>
                        <hr>
                        <div class="p-item-info">
                            <h4 class="p-title"><?php echo $item['name']; ?></h4>
                            <p class="p-price"><span>$</span><?php echo $item['price'];?></p>
                        </div>
                        <a href="product-page.php?pageid=<?php echo $item['id']; ?>&pagename=<?php echo $item['name']; ?>" class="check-it">BUY NOW</a>
                     </div>
                </div>
             <?php } ?>
        </div>
    </div>
</main>


<!-- End Main Content -->






<?php

include $tpl . 'footer.php';
?>