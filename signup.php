<?php 
    session_start();
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
    $PageTitle = 'Login';

    if(isset($_SESSION['user'])){
        header('Location: home.php');   
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $username= $_POST['username'];
        $email= $_POST['email'];
        $password=$_POST['password'];
        $fullname =$_POST['fullname'];
        $hashedpass=sha1($_POST['password']);
        $groupid = "2";
        $checkitem = checkitem("Username","users",$username);           //Check if username is already existed 
        $formerror = array();                                           // Validate the form
        if(empty($username)){ $formerror[] = 'Username cant be empty';}
        if(empty($password)){ $formerror[] = 'Password cant be empty';}
        if(empty($email)){ $formerror[] = 'email cant be empty';}
        if(empty($fullname)){ $formerror[] = 'fullname cant be empty';}
        if($checkitem == 1){ $formerror[] = 'Username is already exist';}
        if(empty($formerror)){                                          //check if there is no error 
            $stmt=$con->prepare("INSERT INTO users(Username, Email, Password, FullName , GroupID)
                                 VALUES (:username , :email , :hashedpass , :fullname , :groupid)" );           
            $stmt->bindparam(":username" , $username);
            $stmt->bindparam(":email" , $email);
            $stmt->bindparam(":hashedpass" , $hashedpass);
            $stmt->bindparam(":fullname" , $fullname);
            $stmt->bindparam(":groupid" , $groupid );
            $stmt->execute();
            echo '<div class="alert alert-success"> check ur mail to activate </div>' ;
            redirect("","check ur mail to activate","index.php");
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title><?php GetTitle(); ?></title>
        <link rel="stylesheet" href="<?php echo $js ;?>bootstrap-4.4.1\dist\css\bootstrap.css"/>
        <link rel="stylesheet" href="<?php echo $js ;?>bootstrap-4.4.1\dist\css\bootstrap.min.css"/> 
        <link rel="stylesheet" href="<?php echo $js ;?>fontawesome-free-5.12.0-web\css\all.min.css">   
        <link rel="stylesheet" href="<?php echo $css ;?>index.css">   
    </head>
    <body>
        <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
            <div class="container">
                <a class="navbar-brand mr-3" href="home.php">
                Home
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    
                </ul>
                <?php if(isset($_SESSION['user'])){ ?>
                <ul class="nav navbar-nav ">
                    <li class="nav-item dropdown">
                    <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-angle-double-down"></i>
                    </a>
                    <div class="dropdown-menu Session" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="members.php?action=edit&userID=<?php echo $_SESSION['userID']?>">Edit Profile</a>
                        <a class="dropdown-item" href="#">Setting</a>
                        <a class="dropdown-item" href="logout.php">Logout</a>
                    </li>
                </ul>
                <?php }else{ ?>
                <ul class="nav navbar-nav ">
                    <li class="nav-item dropdown">
                    <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-angle-double-down"></i>
                    </a>
                    <div class="dropdown-menu NoSession" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="signup.php">Dont have account? Start here</a>
                        <a class="dropdown-item" href="index.php">Hello, Sign in</a>

                    </li>
                </ul>
                <?php }?>
            </div>
        </nav>
        <div class="container">
            <form class="p-5" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <h1 class="text-center">Sign Up</h1>
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
                <button type="submit" class="btn btn-primary col-sm-2 m-auto">Submit</button>
                </div>
            </form>
        </div>
<?php
    include $tpl . "footer.php";
?>