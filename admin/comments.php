<?php
session_start();
$PageTitle = "Comments";
include "init.php";
?>
<div class="container items">
<?php
if(isset($_SESSION['admin'])){
    $action = isset($_GET['action']) ?  $_GET['action'] : 'manage' ;
    if($action == 'manage'){                                                 // Manage Page
        echo '<h1 class="text-center"> Manage Comments</h1>';       
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
        ?>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Comment</th>
                        <th scope="col">Item Name</th>
                        <th scope="col">User Name</th>
                        <th scope="col">Added Date</th>
                        <th scope="col">Control</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($comments as $comment){?>
                    <tr>
                        <th scope="row"><?php echo $comment["Comment_ID"]?></th>
                        <td><?php echo $comment["Comment"]?></td>
                        <td><?php echo $comment["item_Name"]?></td>
                        <td><?php echo $comment["Username"]?></td>
                        <td><?php echo $comment["Add_Date"]?></td>
                        <td>
                            <a href="?action=edit&Comment_ID=<?php echo $comment['Comment_ID']?>" class="btn btn-success mr-1"><i class="fa fa-edit"></i> Edit </a>
                            <a href="?action=delete&Comment_ID=<?php echo $comment['Comment_ID']?>" class="btn btn-danger confirm mr-1"><i class="fa fa-times"></i> Delete </a> 
                            <?php if($comment["Status"] == '0' ){ ?>
                            <a href="?action=approve&Comment_ID=<?php echo $comment['Comment_ID']?>" class="btn btn-primary confirm"><i class="fa fa-times"></i> Approve </a> 
                            <?php } ?>                       
                        </td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
        <?php
    }elseif($action == 'approve'){                                          // Approve Page
        echo '<h1 class="text-center"> Approved Comment</h1>';
        $id=isset($_GET["Comment_ID"]) && is_numeric ($_GET['Comment_ID']) ? intval($_GET['Comment_ID']) : 0 ;
        $stmt = $con->prepare("SELECT * FROM comments WHERE Status = 0 ");
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $stmt = $con->prepare("UPDATE comments SET Status = 1 WHERE Comment_ID = :id ");
            $stmt->bindparam(':id', $id);
            $stmt->execute();
            echo '<div class="alert alert-success">' . $stmt->rowCount() . ' Comment Approved </div>' ;
            redirect("" , "previous");
        }else{
            redirect("There is no such ID" );
        }
    }elseif($action == 'delete'){                                          // Delete Page 
        echo '<h1 class="text-center"> Delete Page</h1>';
            $id = isset($_GET['Comment_ID']) && is_numeric($_GET['Comment_ID']) ? intval($_GET['Comment_ID']) : 0 ;
            $check = checkitem("Comment_ID" , "comments" , $id);
            if($check > 0){
                $stmt=$con->prepare("DELETE FROM comments WHERE Comment_ID = :id ");
                $stmt->bindparam(":id" , $id);
                $stmt->execute();
                echo '<div class="alert alert-danger ">' . $stmt->rowCount() . ' Comment Deleted </div>' ;
                redirect("" , "commets" , "comment.php");
            }else{
                redirect("There is no such ID" , "items" , "items.php");
            }
        }
    
    include  $tpl . 'footer.php';
    }
    else {
        header("location: index.php");
        exit();
    }
        ?>  
    </div>
