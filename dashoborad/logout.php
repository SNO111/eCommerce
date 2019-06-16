<?php 
    $pageTitle = 'Logout';
    $noNavbar = '';
    include('init.php');
    ?>
    <div class="wrapper">
        <div class="login-container">
            <h1 class="goodbye">Goodbye!</h1>
        </div>
	
        <ul class="bg-bubbles">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>


    <?php
    session_start(); 

    session_unset();

    session_destroy();
    ?>