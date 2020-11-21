<?php
// manage members page
session_start();
$PageTitle = 'Members';
include "init.php";
?>
<div class="container">
<?php
if(isset($_SESSION['admin'])){
    $action = isset($_GET['action']) ?  $_GET['action'] : 'manage' ;
    if($action == 'manage'){                                            // Manage Page
        $stmt = $con->prepare("SELECT * FROM users WHERE GroupID = 1 ");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        ?>
        <div class="table-responsive">
            <h1 class="text-center">Members Page</h1>
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        <th scope="col">Fullname</th>
                        <th scope="col">Register Date</th>
                        <th scope="col">Control</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($rows as $row){?>
                    <tr>
                        <th scope="row"><?php echo $row["UserID"]?></th>
                        <td><?php echo $row["Username"]?></td>
                        <td><?php echo $row["Email"]?></td>
                        <td><?php echo $row["FullName"]?></td>
                        <td><?php echo $row["Create"]?></td>
                        <td>
                            <a href="?action=edit&userID=<?php echo $row['UserID']?>" class="btn btn-success mr-1"><i class="fa fa-edit"></i> Edit </a>
                            <a href="?action=delete&userID=<?php echo $row['UserID']?>" class="btn btn-danger confirm mr-1"><i class="fa fa-times"></i> Delete </a>  
                            <?php if($row["GroupID"] == 2 ){ ?>
                            <a href="?action=activate&userID=<?php echo $row['UserID']?>" class="btn btn-primary confirm"><i class="fa fa-times"></i> Activate </a> 
                            <?php } ?>                         
                        </td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
        <a href="?action=add&userID=<?php echo $row['UserID']?>" class="btn btn-primary m-1 "><i class="fa fa-plus"></i> Add New Member </a>
        <a href="?action=pending&userID=<?php echo $row['UserID']?>" class="btn btn-success m-1 float-right"> Go to pending members page <i class="fa fa-angle-double-right"></i></a>
        <?php
    }elseif($action == "pending"){                                     // Pending members page
        $stmt = $con->prepare("SELECT * FROM users WHERE GroupID = 2 ");
        $stmt->execute();
        $rows = $stmt->fetchAll(); 
        ?>
        <div class="table-responsive">
            <h1 class="text-center">Activate Members Page</h1>
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        <th scope="col">Fullname</th>
                        <th scope="col">Register Date</th>
                        <th scope="col">Control</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($rows as $row){?>
                    <tr>
                        <th scope="row"><?php echo $row["UserID"]?></th>
                        <td><?php echo $row["Username"]?></td>
                        <td><?php echo $row["Email"]?></td>
                        <td><?php echo $row["FullName"]?></td>
                        <td><?php echo $row["Create"]?></td>
                        <td>
                            <a href="?action=edit&userID=<?php echo $row['UserID']?>" class="btn btn-success mr-1"><i class="fa fa-edit"></i> Edit </a>
                            <a href="?action=delete&userID=<?php echo $row['UserID']?>" class="btn btn-danger confirm mr-1"><i class="fa fa-times"></i> Delete </a>  
                            <?php if($row["GroupID"] == 2 ){ ?>
                            <a href="?action=activate&userID=<?php echo $row['UserID']?>" class="btn btn-primary confirm"><i class="fa fa-times"></i> Activate </a> 
                            <?php } ?>                         
                        </td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
        <?php                             
    }elseif($action == "activate"){                                     // Activate Pending Member Page                                   
        echo '<h1 class="text-center"> Activate member</h1>';
        $userID = isset($_GET['userID']) && is_numeric($_GET['userID']) ? intval($_GET['userID']) : 0 ;
        $stmt = $con->prepare("SELECT * FROM users WHERE GroupID = 2 ");
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $stmt = $con->prepare("UPDATE users SET GroupID = 1 WHERE UserID = ? ");
            $stmt->execute(array($userID ));
            echo '<div class="alert alert-success">' . $stmt->rowCount() . ' Member Activated </div>' ;
            redirect("" , "previous");
        }else{
            redirect("There is no such ID" );
        }
    }elseif($action == 'add'){                                          //Add page 
    ?>
        <div class="container">
        <h1 class="text-center">Add Page</h1>
        <form class="" action="?action=insert" method="POST" enctype="multipart/form-data">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Username</label>
                <div class="col-sm-10">
                    <input type="text" name="username" class="form-control" placeholder="Username" required="required"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                    <input type="password" name="password" class="password form-control" autocomplete="new-password" placeholder="Password" required="required">
                    <i class="show-pass fa fa-eye"></i>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" name="email" class="form-control" placeholder="Email" autocomplete="off" required="required">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Fullname</label>
                <div class="col-sm-10">
                    <input type="text" name="fullname" class="form-control" placeholder="Fullname" autocomplete="off" required="required">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="File1">User Image</label>
                <div class="col-sm-10">
                    <input type="file" name="image" class="form-control-file" id="File1">
                </div>
            </div>
            <div class="form-group row">
               <button type="submit" class="btn btn-primary col-sm-2 m-auto">Submit</button>
            </div>
        </form>
        </div>
    <?php
    }elseif($action == 'edit'){                                       // Edit page
       // Check if Get request userID is numeric and get the integer value of it 
       $userid = isset($_GET['userID']) && is_numeric($_GET['userID']) ? intval($_GET['userID']) : 0 ; 

       $stmt=$con->prepare("SELECT * FROM users WHERE userID = ? "); // Select all data on this ID
       $stmt->execute(array($userid));                               // Execute  Query
       $row = $stmt->fetch();                                        // Fetch The Data
       if($stmt->rowCount() > 0){                                    // check if there is such ID
        ?>
        <div class="container">
        <h1 class="text-center">Edit Page</h1>
        <form class="" action="?action=update" method="POST">
            <input type="hidden" value="<?php echo $userid ;?>" name="userID"/>
            <input type="hidden" value="<?php echo $row['Password']?>" name="oldpassword"/>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Username</label>
                <div class="col-sm-10">
                    <input type="text" name="username" class="form-control" placeholder="Username" value="<?php echo $row['Username'] ; ?>" autocomplete="off" required="required"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                    <input type="password" name="newpassword" class="password form-control" autocomplete="new-password" placeholder="Leave empty if you dont want to change">
                    <i class="show-pass fa fa-eye"></i>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo $row['Email'] ; ?>" autocomplete="off" required="required">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Fullname</label>
                <div class="col-sm-10">
                    <input type="text" name="fullname" class="form-control" placeholder="Fullname" value="<?php echo $row['FullName'] ; ?>" autocomplete="off" >
                </div>
            </div>
            <div class="form-group row">
               <button type="submit" class="btn btn-primary col-sm-2 m-auto">Submit</button>
            </div>
        </form>
        </div>
        <?php

       }else{
        redirect("there is no such ID");
       }
    }elseif($action == 'update'){                                    // Update page
        echo '<h1 class="text-center"> Update Page</h1>';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $userID= $_POST['userID'];
            $username= $_POST['username'];
            $email= $_POST['email'];
            $oldpassword = $_POST['oldpassword'] ;
            $newpassword = $_POST['newpassword'];
            $fullname =$_POST['fullname'];
            $pass = empty($newpassword) ? ($oldpassword) : sha1($newpassword) ;  // password check 
            $checkitem = checkitem("UserName","users",$username);               //Check if username is already existed 
            $formerror = array();                                               // Validate the form
            if(empty($username)){ $formerror[] = 'Username cant be empty';}
            if(empty($email)){ $formerror[] = 'email cant be empty';}
            if(empty($fullname)){ $formerror[] = 'fullname cant be empty';}
            if($checkitem == 1){ $formerror[] = 'Username is already exist';}
            if(empty($formerror)){                                              //check if there is no error 
                $stmt = $con->prepare("UPDATE users SET Username = ? , Email = ? , FullName = ? , Password = ? WHERE UserID = ? ");
                $stmt->execute(array($username , $email , $fullname , $pass , $userID ));
                echo '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Updated </div>' ;
                redirect("","members","members.php" );
            }else{
                foreach($formerror as $error){
                    echo '<div class = "alert alert-danger">' . $error . '</div>';
                }
                redirect("","previous" );
            }
        }
        else{
            redirect("Sorry u cant enter this page directly","home" );
        }        
    }elseif($action == 'insert'){                                    // Insert page
        echo '<h1 class="text-center"> Insert Page</h1>';                                 
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $username= $_POST['username'];
            $email= $_POST['email'];
            $password=$_POST['password'];
            $fullname =$_POST['fullname'];
            // Upload Image
            $image=$_FILES['image'];
            $img_name=$image['name'];
            $img_type=$image['type'];
            $img_tmp_name=$image['tmp_name'];
            $img_size=$image['size'];
            $img_extension = explode('.',$img_name);
            $allowed_extension = array('jpg' , 'jpeg' , 'png');
            $image_extension = end($img_extension);

            $hashedpass=sha1($_POST['password']);
            $groupid = "1";
            $checkitem = checkitem("Username","users",$username);           //Check if username is already existed 
            $formerror = array();                                           // Validate the form
            if(empty($username)){ $formerror[] = 'Username cant be empty';}
            if(empty($password)){ $formerror[] = 'Password cant be empty';}
            if(empty($email)){ $formerror[] = 'email cant be empty';}
            if(empty($fullname)){ $formerror[] = 'fullname cant be empty';}
            if(!empty($image_extension) && !in_array($image_extension,$allowed_extension)){ $formerror[] = 'This extension is not allowed';}
            if($checkitem == 1){ $formerror[] = 'Username is already exist';}
            if(empty($formerror)){                                          //check if there is no error 
                $stmt=$con->prepare("INSERT INTO users(Username, Email, Password, FullName , GroupID , User_Image)
                 VALUES (:username , :email , :hashedpass , :fullname , :groupid , :userimage)" );           
                $stmt->bindparam(":username" , $username);
                $stmt->bindparam(":email" , $email);
                $stmt->bindparam(":hashedpass" , $hashedpass);
                $stmt->bindparam(":fullname" , $fullname);
                $stmt->bindparam(":groupid" , $groupid );
                $stmt->bindparam(":userimage" , $img_name );
                $stmt->execute();
                move_uploaded_file($img_tmp_name , 'uploads//'.$img_name);     
                echo '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Added </div>' ;
               redirect("","members","members.php");
            }else{
                foreach($formerror as $error){
                    echo '<div class = "alert alert-danger">' . $error . '</div>';
                }
                redirect("","previous");
            }
        }else{
            redirect("u cant access this page directly","home" );
        }
    }elseif($action == 'delete'){                                // Delete Page 
        echo '<h1 class="text-center"> Delete Page</h1>';
            $userID = isset($_GET['userID']) && is_numeric($_GET['userID']) ? intval($_GET['userID']) : 0 ;
            $check = checkitem("UserID" , "users" , $userID);
            if($check > 0){
                $delete=$con->prepare("DELETE FROM users WHERE UserID = :userid ");
                $delete->bindparam(":userid" , $userID);
                $delete->execute();
                $select = $con->prepare("SELECT User_Image FROM users WHERE UserID =:userid ");
                $select->bindparam(":userid" , $userID);
                $select->execute();
                $row=$select->fetch();
                $image = $row['User_Image'];
                unlink ('uploads//'.$image);
                echo $image;

            //  reset_id("users","UserID");
                echo '<div class="alert alert-danger ">' . $delete->rowCount() . ' Record Deleted </div>' ;
                redirect("" , "members" , "members.php");
            }else{
                redirect("There is no such ID" , "members" , "members.php");
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

        
   
