<?php 
    session_start();
    $pageTilte = 'categories';
    include('init.php');?>

<main>
    <div class="container">
        <h1 class="text-center"><?php echo str_replace('-', ' ', $_GET['pagename']); ?></h1>

        <div class="row">
            <?php 
                $pageid =  $_GET['pageid'];
                $allItems =getAllFrom('*', 'items', "WHERE cat_item_id = {$pageid}", '', 'id');
                foreach ($allItems as $item) {
               
            ?>
                <div class="col-md-4 col-lg-4">
                    <div class="product-item text-center">
                        <div class="p-item-img">
                            <img class="img-fluid" src="dashoborad/uploaded/images/<?php echo $item['item_image'];?>" alt="">
                        </div>
                        <hr>
                        <div class="p-item-info text-center">
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




<?php
    include $tpl . 'footer.php';
?>