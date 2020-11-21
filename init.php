<?php

    include "admin/connect.php";

    //routes

    $tpl = 'includes/templates/';   // templates directory
    $lang = 'includes/languages/';  // language directory
    $func = 'includes/functions/';   // function directory
    $css = 'layout/css/';           // css directory
    $js  = 'layout/js/';            // js directory
    
    // important files
    
    include $func . 'functions.php';
    include $lang . 'en.php';
    include $tpl  . 'header.php'; 

    if(!isset($noNavbar)){ include $tpl . 'navbar.php'; }    

    

?>