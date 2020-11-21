<?php

// tilte function v1.0
    function GetTitle(){                                                                
        global $PageTitle ;
        if(isset($PageTitle)){
            echo $PageTitle ;
        }else{
            echo 'default';        
        }
    }

/*
     Redirect function v2.0
     $error  = echo the error [if there is no error dont forget to set parameter =""]
     $page   = name of page u will redirect to
     $url    = the link u want to redirect
     $second = seconds before redirecting
*/
    function redirect($error = "" , $page = 'home' , $url = null , $second = 3 ){
    
        $url = isset($_SERVER["HTTP_REFERER"]) && $_SERVER["HTTP_REFERER"] != "" && $url === null? $_SERVER["HTTP_REFERER"] : $url ;
        if($error != ""){
            echo '<div class="alert alert-danger">' . $error . '</div>' ; 
        }
        echo '<div class="alert alert-primary"> You will be redirected to ' . $page . ' page after ' . $second . ' second </div>' ;
        header("refresh: $second;url=$url"); 
    }

 /* 
    Check items function v2.0
    $item  = the item u wanna select
    $table =  the taple u select from
    $value = the value u wanna select
    note that -> if u wanna caluclate items in DB set $value = null 
              -> if u wanna check item in DB set $value = value
 */
    function checkitem($item , $table , $value = null){
        global $con;
        if($value === null){
            $stmt=$con->prepare("SELECT COUNT($item) FROM $table ");
            $stmt->execute();       
            return $stmt->fetchColumn(); 
        }else{
            $stmt=$con->prepare("SELECT $item FROM $table WHERE $item = ? ");
            $stmt->execute(array($value));
            return $stmt->rowCount(); 
        }
    }
/*
    Get latest function v1.0  
    $item  = the item u wanna get
    $table = the taple u select from
    $order =  which order u wanna items order with
    $limit = number of elemets u want to get
*/
    function getlatest( $item , $table , $order , $limit = "5"){
        global $con;
        $stmt=$con->prepare("SELECT $item FROM $table ORDER BY $order DESC LIMIT $limit ");
        $stmt->execute();       
        return $stmt->fetchAll();
    }    

/*  Radio button function v1.0
    $item = the item u wanna get
    $table = the taple u select from
    $id = the value u wanna select
*/
    function radio($item , $table , $id){
        global $con ;
        $stmt=$con->prepare("SELECT `$item` FROM `$table` WHERE `ID` = $id ");
        $stmt->execute();
        $row = $stmt->fetch(); 
        $value = ($row["$item"] == '0') ? '0' :'1';
        return $value ;
    }

/*
    Reset ID order
    $table = the table which u reset its order
*/
    function reset_id($table,$id){
        global $con;
        $stmt=$con->prepare("ALTER TABLE $table DROP $id ; ALTER TABLE $table ADD $id INT PRIMARY KEY AUTO_INCREMENT;");
        $stmt->execute();
    }



/*
function select($item , $table  , $values = array() , $column = null , $value = null ){
    global $con;
    if($value === null && $column === null){                                                           // No value
            $stmt=$con->prepare("SELECT $item FROM $table ");
            $stmt->execute();       
    }else{                                                                         // Select with value
        $stmt=$con->prepare("SELECT $item FROM $table WHERE $column ");
        $stmt->execute(array("$value"));
    }
    return $stmt->fetchAll(); 
}

function update($item , $from=array() , $value = ''){
    global $con;
    $stmt = $con->prepare("UPDATE $item SET Username = ? , Email = ? , FullName = ? , Password = ? WHERE UserID = ? ");
    $stmt->execute(array($username , $email , $fullname , $pass , $userID ));
}

function insert($select = '*' , $from , $value = ''){
    global $con;
    $stmt=$con->prepare("SELECT $select FROM $from WHERE $select = ? ");
    $stmt->execute(array($value));
}

function delete($select = '*' , $from , $value = ''){
    global $con;
    $stmt=$con->prepare("SELECT $select FROM $from WHERE $select = ? ");
    $stmt->execute(array($value));
}
*/

?>