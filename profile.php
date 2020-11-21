<?php
    session_start();
    include "init.php";
    if(isset($_SESSION['user'])){
        $username =  $_SESSION['user'] ; 
        $stmt=$con->prepare("SELECT * FROM users WHERE Username = :username "); // Select all data on this ID
        $stmt->bindparam(":username" , $username);
        $stmt->execute();                                                       // Execute  Query
        $row = $stmt->fetch();                                                  // Fetch The Data
        if($stmt->rowCount() > 0){                                              // check if there is such ID
            ?>
            <div class="container">
            <form class="p-5 profile" action="?action=update" method="POST">
                <h1 class="text-center">My Profile</h1>
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
    }else{
    header("Location: index.php");
    }
    

    include $tpl . "footer.php";

?>