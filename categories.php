<?php 
    session_start();
    $pageTilte = 'categories';
    include('init.php');
    ?>
    <main id="categories-page">
        <div class="container">
            <div class="row">
                <?php 
                    $categories = getAllFrom('*', 'categories', "WHERE parent = 0", '', 'id');
                    foreach ($categories as $cat) {
                ?>
                    <div class="col-md-4">
                        <div class="category-box">
                            <div class="cat-img">
                                <?php if(!empty($cat['cat_image'])) { ?>
                                        <img class="img-fluid" src="dashoborad/uploaded/images/<?php echo $cat['cat_image'];?>" alt="">
                                <?php  } else {
                                        echo '<img class="img-fluid" src="uploaded/images/" alt="">';
                                } ?>
                            </div>
                            <h2 class="category-title"><?php echo $cat['name'];?></h2>
                            <div class="cat-sub">
                                <ul class="list-unstyled">
                                    <li class="item-nav">
                                        <a href="#" class="nav-link"></a>
                                        <?php 
                                            $subCats = getAllFrom('*', 'categories', "WHERE parent = {$cat['id']}", '', 'id');
                                            foreach ($subCats as $sub) { ?>
                                                <a href="category.php?pageid=<?php echo $sub['id'] . '&pagename=' . str_replace(' ', '-', $sub['name']);?>" class="sub-category"><?php echo $sub['name'];?></a>
                                                <br>
                                        <?php } ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                <?php } ?>
            </div>
        </div>
    </main>

<?php 
    include $tpl . 'footer.php';


?>