<?php 
session_start();
$pageTitle = 'Product-page';
include('init.php');
 $product_id = $_GET['pageid'];
  $stmt = $db->prepare("SELECT items.*, categories.name AS cat_name
                        FROM items
                        INNER JOIN categories
                        ON
                        items.cat_item_id = categories.id
                        WHERE items.id = ?");
  $stmt->execute(array($product_id));
  $products = $stmt->fetchAll();
  foreach ($products as $pro) {
    $echoError = '';
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      // Get variables sfrom the form
      $user             = filter_var($_POST['user'], FILTER_SANITIZE_STRING);
      $email            = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
      $com              = filter_var($_POST['review'], FILTER_SANITIZE_STRING);
      $rating            = $_POST['rating'];
      $itemid           = $_POST['item'];


      $formErrors = array();
      //  Check if there is no error proceed the insert operation
      if(empty($com)) {
          $formErrors[] = 'Please leave a review - Field can\'t be empty';
      }
      if (empty($user)) {
          $formErrors[] = 'User Field can\'t be empty';
      }
      if (empty($email)) {
          $formErrors[] = 'email Field can\'t be empty';
      }
      foreach ($formErrors as $error) {
          $echError = '<div class="alert-error">' . $error . '</div>';
          reDirecthome($msgStatus,'back', 5);
      }

      
      // Update the Database with these new info
      if (empty($formErrors)) {
            if(isset($_SESSION{"username"})) {
              $stmt = $db->prepare("INSERT INTO  comments (comment, comment_date, rating, user_id, item_id) 
                                    VALUES (:zcom, now(), :zrating, :zuserid, :zitemid)");
              $stmtCount = $stmt->execute(array(
                  'zcom'        => $com,
                  'zuserid'     => $user,
                  'zrating'     => $rating,
                  'zitemid'     => $itemid
              ));

              // Echo success message
              if ($stmtCount) {
                  $msgStatus = '<div class="alert-success">Comment Added</div>';
                  reDirecthome($msgStatus, 'back');
              } else {
                  $msgStatus = '<div class="alert-error">Something went wrong!</div>';
                  reDirecthome($msgStatus);
              }
            } else {
              $echoError = '<p class="alert alert-danger">please login in first to review</p>';
            }
       }
      }

?>
<main>
  <div class="container">

    <div class="row">
    <div class="product-wrapper">
      <div class="col-md-7 col-log-7 col-sm-12  text-center">
         <!-- Left Column / Headphones Image -->
        <div class="left-column text-left">
          <img class="active img-fluid"data-image="black" src="dashoborad/uploaded/images/<?php echo $pro['item_image'];?>" alt="">
        </div>
      </div>
      <div class="col-md-5  col-lg-5 col-sm-12">
          <!-- Right Column -->
          <div class="right-column">

          <!-- Product Description -->
          <div class="product-description">
            <span><?php echo $pro['cat_name'];?></span>
            <h1><?php echo $pro['name'];?></h1>
            <p><?php echo $pro['description'];?></p>
          </div>

          <!-- Product Configuration -->
          <div class="product-configuration">

            <!-- Product Color -->
            <div class="product-color">
              <span>Color</span>

              <div class="color-choose">
                <div>
                  <input data-image="red" type="radio" id="red" name="color" value="red">
                  <label for="red"><span></span></label>
                </div>
                <div>
                  <input data-image="blue" type="radio" id="blue" name="color" value="blue">
                  <label for="blue"><span></span></label>
                </div>
                <div>
                  <input data-image="black" type="radio" id="black" name="color" value="black" >
                  <label for="black"><span></span></label>
                </div>
              </div>
            </div>

            <!-- Cable Configuration -->
            <div class="cable-config">
              <span>Cable configuration</span>

              <div class="cable-choose">
                <button>Straight</button>
                <button>Coiled</button>
                <button>Long-coiled</button>
              </div>

              <a href="#">How to configurate your headphones</a>
            </div>
          </div>

          <!-- Product Pricing -->
          <div class="product-price">
            <span><?php echo $pro['price'];?>$</span>
            <form  action="cart.php" method="POST">
                <input type="number" name="quantity" value="1" min="<?=$pro['quantity']?>" placeholder="Quantity" required>
                <input type="hidden" name="product_id" value="<?=$pro['id']?>">
                <input class="cart-btn" type="submit" value="Add To Cart">
            </form>
          </div>
          </div>
      
      </div>
    </div>
  </div>
  </div>
  <hr>
  <div class="container">
    <div id="reviews">
      <h5 class="p-review">reviews <span>(3)</span></h5>
      <hr>
      <div class="row">
        <div class="col-md-6">
          <?php
            $stmt = $db->prepare("SELECT comments.*, users.username, items.name AS item_name
                                  FROM comments
                                  INNER JOIN users
                                  ON
                                  comments.user_id = users.id
                                  INNER JOIN items
                                  ON
                                  comments.item_id = items.id 
                                  WHERE comments.item_id = {$pro['id']} ORDER BY comments.id LIMIT 3");
            $stmt->execute();
            $coms = $stmt->fetchAll();
            $count = $stmt->rowCount();
            if (empty($count)) {
              echo '<p>There is no review on this product<p/>';
            } else { 
              foreach ($coms as $com) { ?>
              <div class="product-information">
                  <div class="product-review-info float-left">
                        <h6 class="posted-by"><i class="fa fa-user"></i><?php echo ' ' . $com['username'];?></h6>
                        <span class="posted-date" ><i class="fa fa-clock-o"></i><?php echo ' ' .  $com['comment_date'];?></span>
                    </div>
                    <div class="product-review-rating float-right">
                      <?php 
                          if ($com['rating'] == 0) { ?>
                              <div class="no-review">
                                    <i class="fa fa-star empty"></i>
                                    <i class="fa fa-star empty"></i>
                                    <i class="fa fa-star empty"></i>
                                    <i class="fa fa-star empty"></i>
                                    <i class="fa fa-star empty"></i>
                              </div>
                         <?php  } elseif ($com['rating'] == 1) { ?>
                              <div class="one-review">
                                  <i class="fa fa-start"></i>
                                  <i class="fa fa-star empty"></i>
                                  <i class="fa fa-star empty"></i>
                                  <i class="fa fa-star empty"></i>
                                  <i class="fa fa-star empty"></i>
                              </div>
                          <?php } elseif ($com['rating'] == 2) { ?>
                              <div class="two-review">
                                  <i class="fa fa-star"></i>
                                  <i class="fa fa-star"></i>
                                  <i class="fa fa-star empty"></i>
                                  <i class="fa fa-star empty"></i>
                                  <i class="fa fa-star empty"></i>
                              </div>
                          <?php } elseif ($com['rating'] == 3) { ?>
                              <div class="three-review">
                                  <i class="fa fa-star"></i>
                                  <i class="fa fa-star"></i>
                                  <i class="fa fa-star"></i>
                                  <i class="fa fa-star empty"></i>
                                  <i class="fa fa-star empty"></i>
                                </div>
                          <?php } elseif ($com['rating'] == 4) { ?>
                                <div class="four-review">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star empty"></i>
                                  </div>
                          <?php } else { ?>
                                  <div class="five-review">
                                      <i class="fa fa-star"></i>
                                      <i class="fa fa-star"></i>
                                      <i class="fa fa-star"></i>
                                      <i class="fa fa-star"></i>
                                      <i class="fa fa-star"></i>
                                    </div>
                          <?php } ?>

                    </div>
                </div>
                <div class="clearfix"></div>
                <p class="product-comment"><?php echo $com['comment'];?></p>

            <?php }
             } // endif ?>

      </div>
        <div class="col-md-6">
          <form class="review-form" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
          <input type="hidden" value="<?php echo $pro['id'];?>">
              <input class="form-control" type="text" name="user" placeholder="Your Name">
              <input class="form-control" type="text" name="email" placeholder="Email Address">
              <textarea class="form-control" name="review"  placeholder="Your Review"></textarea>
              <fieldset class="rating">
                  <legend class="float-left" >Please rate:</legend>
                  <input class="float-right" type="radio" id="star5" name="rating" value="5" /><label for="star5" title="Rocks!">5 stars</label>
                  <input class="float-right"  type="radio" id="star4" name="rating" value="4" /><label for="star4" title="Pretty good">4 stars</label>
                  <input class="float-right"  type="radio" id="star3" name="rating" value="3" /><label for="star3" title="Meh">3 stars</label>
                  <input class="float-right"  type="radio" id="star2" name="rating" value="2" /><label for="star2" title="Kinda bad">2 stars</label>
                  <input class="float-right"  type="radio" id="star1" name="rating" value="1" /><label for="star1" title="Sucks big time">1 star</label>
              </fieldset>
              <button type="submit" class="btn">SUBMIT</button>
        </form>
        <?php echo $echoError; ?>
        </div>

      </div>
    </div>
  </div>
</main>  
<?php 
  }
include $tpl . 'footer.php'; ?>