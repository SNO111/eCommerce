<?php 
include('dashoborad/conPDO.php');

$sessionUser = '';
if (isset($_SESSION['user'])) {
    $sessionUser = $_SESSION['user'];
}

$tpl = 'includes/templates/';
$func = 'dashoborad/inc/funcs/';
$css = 'layout/css/';
$js = 'layout/js/';

include $func . 'functions.php';
include $tpl . 'header.php';
if(!isset($noNavbar)) {
    include $tpl . 'navbar.php';
}





