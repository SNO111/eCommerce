<?php 

    $dbhost = 'localhost';
    $dbname = 'cmd';
    $dbuser = 'root';
    $dbpass = '';
    $options = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    );
 
   try {

        $db = new PDO("mysql:host=$dbhost;dbname=$dbname;", $dbuser, $dbpass, $options);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      //  echo 'You Are Connected Wlecome to Database <b> [ ' . $dbname . ' ] </b>';

   } catch (PDOException $e) {

       echo 'ERROR: ' . $e->getMessage();

   }