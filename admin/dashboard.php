<?php
session_start();
include "init.php";

if(isset($_SESSION['admin'])){
    $PageTitle = 'Dashboard';
    // Start dashboard page
    ?>
    
    <div class="container">
        <h1 class="text-center">Dashboard</h1>
        <div class="row home-state">
            <div class="col-md-3">
                <a href="members.php">
                    <div class="state state-member">
                        Total Members
                        <span><?php echo checkitem("GroupID" , "users" , "1"); ?></span>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="members.php?action=pending">
                    <div class="state state-pending">
                        Pending Members
                        <span><?php echo checkitem("GroupID" , "users" , "2");  ?></span>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="items.php">
                    <div class="state state-item">
                        Total Items
                        <span><?php echo checkitem("*" , "items");  ?></span>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="comments.php">
                    <div class="state state-comment">
                        Total Commets
                        <span><?php echo checkitem("*" , "comments");  ?></span>
                    </div>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php $latest_users = "4" ?>
                        <i class="fa fa-user"></i> Latest <?php echo $latest_users  ?> Registered Users<i class="fa fa-plus float-right mr-2 view "></i>
                    </div>
                    <div class="panel-body full-view">
                        <ul class="list-group list-group-flush">
                            <?php 
                            $latest = getlatest("*","users","UserID" , $latest_users);
                            foreach($latest as $user){
                            ?>
                                <li class="list-group-item">
                                    <?php echo $user["Username"]?>
                                    <a href="members.php?action=edit&userID=<?php echo $user['UserID']?>" class="btn btn-success btn-sm float-right"><i class="fa fa-edit"></i> Edit </a>
                                    <?php if($user["GroupID"] == '2'){?>
                                    <a href="members.php?action=activate&userID=<?php echo $user['UserID']?>" class="btn btn-primary btn-sm float-right mr-2 confirm"><i class="fa fa-check"></i> Activate </a> 
                                <?php } ?>
                                </li>
                            <?php } ?>   
                        </ul>         
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php $latest_items = "4" ?>
                        <i class="fa fa-user"></i> Latest <?php echo $latest_items  ?> Items<i class="fa fa-plus float-right mr-2 view "></i>
                    </div>
                    <div class="panel-body full-view">
                        <ul class="list-group list-group-flush">
                            <?php 
                            $latest = getlatest("*","items","ID" , $latest_items);
                            foreach($latest as $item){
                            ?>
                                <li class="list-group-item">
                                    <?php echo $item["Name"]?>
                                    <a href="items.php?action=edit&ID=<?php echo $item['ID']?>" class="btn btn-success btn-sm float-right"><i class="fa fa-edit"></i> Edit </a>
                                    <?php if($item["Approve"] == '0'){?>
                                    <a href="items.php?action=activate&ID=<?php echo $item['ID']?>" class="btn btn-primary btn-sm float-right mr-2 confirm"><i class="fa fa-check"></i> Approved </a> 
                                <?php } ?>
                                </li>
                            <?php } ?>   
                        </ul>         
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php $latest_comments = "4" ?>
                        <i class="fa fa-user"></i> Latest <?php echo $latest_comments  ?> Comments <i class="fa fa-plus float-right mr-2 view "></i>
                    </div>
                    <div class="panel-body full-view">
                        <ul class="list-group list-group-flush">
                            <?php 
                            $stmt = $con->prepare("SELECT 
                                                        comments.* , items.Name AS item_Name , users.Username
                                                    FROM 
                                                        comments 
                                                    INNER JOIN 
                                                        items 
                                                    ON 
                                                        items.ID = comments.Item_ID 
                                                    INNER JOIN 
                                                        users 
                                                    ON 
                                                        users.UserID = comments.User_ID ");
                            $stmt->execute();
                            $comments = $stmt->fetchAll();
                            foreach($comments as $comment){
                            ?>
                                <li class="list-group-item">
                                    <span class="" ><?php echo $comment["Comment"]?></span>
                                    <span>
                                    <a href="commnets.php?action=delete&Comment_ID=<?php echo $comment['Comment_ID']?>" class="btn btn-danger btn-sm float-right confirm"><i class="fa fa-times"></i> Delete </a> 
                                    <?php if($comment["Status"] == '0'){?>
                                    <a href="commnets.php?action=approve&Comment_ID=<?php echo $comment['Comment_ID']?>" class="btn btn-primary btn-sm float-right mr-2 confirm"><i class="fa fa-check"></i> Activate </a> 
                                <?php } ?>
                                </li>
                            <?php } ?>   
                        </ul>         
                    </div>
                </div>
            </div>
    </div>
        
    
    <?php
    // End dashboard page
    
    
    include  $tpl . 'footer.php'; 
}
else {
    header("location: index.php");
    exit();
}






?>