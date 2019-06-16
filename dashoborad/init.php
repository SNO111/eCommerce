<?php 

include('conPDO.php');

$tpl = 'inc/templ/';
$func = 'inc/funcs/';
$css = 'layout/css/';
$js = 'layout/js/';

include $func . 'functions.php';
include $tpl . 'header.php';
if(!isset($noNavbar)) {
    include $tpl . 'navbar.php';
}









?>