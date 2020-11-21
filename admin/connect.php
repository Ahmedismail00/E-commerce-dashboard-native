<?php

    $dsn = 'mysql:host=localhost;dbname=shop';
    $user = 'root';
    $password = '';
    $options = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    );

    try {

        $con = new PDO($dsn , $user , $password , $options);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
    } catch (PDOException $error) {
       
        echo 'error ' . $error->getMessage();
    }


?>