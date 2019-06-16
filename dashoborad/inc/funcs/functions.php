<?php
/*
** Function  to echo the page title in case there's a var $pageTilte 
** And Default on other page
*/

function getTitle() {
    global $pageTilte;
     echo $pageTilte = isset($pageTilte) ? $pageTilte : 'Default'; 
}

/*
** Function to get informations from database v0.1
*/

  function getAllFrom($select, $from, $where = Null, $and = Null, $orderby) {
    global $db;
    $stmt = $db->prepare("SELECT $select FROM $from  $where  $and ORDER BY $orderby DESC");
    $stmt->execute();
    $queries = $stmt->fetchAll();
    return $queries;

  }


/*
** 'getAllQuery' Function to fetch all needed information from DATABASE v1.0
*/
function getAllQuery($selector, $from, $order, $limit = 5) {
    global $db;
    $stmt = $db->prepare("SELECT $selector FROM $from ORDER BY $order DESC LIMIT $limit");
    $stmt->execute();
    $queries = $stmt->fetchAll();
    return $queries;
}

/*
** 'getInnerQuery' Function to fetch all needed information from DATABASE v1.0
*/
function getInnerQuery($selector, $from, $joined, $on_table, $row, $on_table2, $row2,  $order, $limit = 5) {
    global $db;
    $stmt = $db->prepare("SELECT $selector FROM $from INNER JOIN $joined ON $on_table.$row = $on_table2.$row2 ORDER BY $order DESC LIMIT $limit");
    $stmt->execute();
    $queries = $stmt->fetchAll();
    return $queries;
}
/*
** 'getInnerQuery' Function to fetch all needed information from DATABASE v1.1
*/
function getInnerQuery2($selector, $from, $joined, $on_table, $row, $on_table2, $row2, $joined2, $on_table3, $row3, $on_table4, $row4,  $order, $order_row,  $limit = 5) {
    global $db;
    $stmt = $db->prepare("SELECT $selector
                            FROM $from
                            INNER JOIN $joined
                            ON
                            $on_table.$row = $on_table2.$row2
                            INNER JOIN $joined2
                            ON
                            $on_table3.$row3 = $on_table4.$row4
                            ORDER BY $order.$order_row DESC LIMIT $limit");
    $stmt->execute();
    $queries = $stmt->fetchAll();
    return $queries;
}
/*
** Count - Function to count thing v1.0
*/
function rowCount($selector, $from) {
    global $db;
    $stmt = $db->prepare("SELECT $selector FROM $from");
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($count < 1) {
        echo '0';
    }
    return $count;
}

/*
** Redirect Function v1.0
** This function to redirect to either home page or prevoius page based on HTTP_REFERER
** With # parameters [The Message], [The URL], [Second]
*/
function reDirecthome($msgStatus, $url = NULL, $seconds = 4 ) {
    if ($url === NULL) {

        $url = 'index.php';
        $link = 'Homepage';

    } else {
        $url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '' ? $_SERVER['HTTP_REFERER'] : 'index.php';
        
        $link = 'Previous Page';
        
    }
    echo $msgStatus;
    echo '<div class="alert-msg">You will be redirect to ' . $link . ' after ' . $seconds . '</div>';
    header("refresh:$seconds;url=$url");
    exit();

}