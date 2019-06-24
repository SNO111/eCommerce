<?php 
    session_start();
    $pageTilte = 'Display search...';
    include('init.php'); 


    // create a new function 
    if (isset($_SERVER['REQUEST_METHOD']) == 'POST') { ?>
         <div class="container">
    <!-- Main content -->
    <section>
      <div class="row">
          <div class="col-sm-9">
          <?php
                 $keyword = '';
                 $stmt = $db->prepare("SELECT COUNT(*) AS numrows FROM items WHERE name LIKE :keyword");
                 $stmt->execute(['keyword' => '%'.$_POST['keyword'].'%']);
                 $row = $stmt->fetch();
                 if($row['numrows'] < 1){
                     echo '<h1 class="page-header">No results found for <i>'.$_POST['keyword'].'</i></h1>';
                 }
                 else{
                     echo '<h1 class="page-header">Search results for <i>'.$_POST['keyword'].'</i></h1>';
                     $inc = 3;	
                     $stmt = $db->prepare("SELECT * FROM items WHERE name LIKE :keyword");
                     $stmt->execute(['keyword' => '%'.$_POST['keyword'].'%']);
              
                     foreach ($stmt as $row) {
                         $highlighted = preg_filter('/' . preg_quote($_POST['keyword'], '/') . '/i', '<b>$0</b>', $row['name']);
                         $inc = ($inc == 3) ? 1 : $inc + 1;
                            if($inc == 1) echo "<div class='row'>";
                            echo "
                                <div class='col-sm-4 img-thumbnail'>
                                    <div class='box box-solid'>
                                        <div class='box-body prod-body'>
                                            <img src='dashoborad/uploaded/images/". $row['item_image'] ."' width='100%' height='230px'>
                                            <h5><a href='product-page.php?pageid=" . $row['id'] . "&pagename=" . $row['name'] . "'>" . $highlighted . "</a></h5>
                                        </div>
                                        <div class='box-footer'>
                                            <b>&#36; ".number_format($row['price'], 2)."</b>
                                        </div>
                                    </div>
                                </div>
                            ";
                            if($inc == 3) echo "</div>";
                     }
                     if($inc == 1) echo "<div class='col-sm-4'></div><div class='col-sm-4'></div></div>"; 
                     if($inc == 2) echo "<div class='col-sm-4'></div></div>";
                     
         
              }


             ?> 
          </div>
      </div>
    </section>
  </div>
    <?php } else {
        $msgStatus = '<div class="alert alert-danger">Sorry, you can\'t browse this page directly</div>';
        reDirecthome($msgStatus);
    }
    
    
    ?>
   


    <?php  include $tpl . 'footer.php';  ?>