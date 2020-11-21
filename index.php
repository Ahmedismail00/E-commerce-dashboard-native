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
        $email = $_POST['email'];
        $password = $_POST['password'];
        $hashedpassword=sha1($password);
        $stmt=$con->prepare("SELECT * FROM `users` WHERE `Email` = :email AND `Password` = :password ");
        $stmt->bindparam(':email' , $email );
        $stmt->bindparam(':password' , $hashedpassword);
        $stmt->execute();
        $row = $stmt->fetch();
        $username=$row['Username'];
        if($stmt->rowcount() > 0 ){
            session_start();
            $_SESSION['user'] = $username;
            header('location:home.php');
            exit();
        }else{
            redirect("ur email or password are not correct");
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
            <form class="p-5 login" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                <h1 class="text-secondary mb-5 col-12 text-center"> Log in to ur shop  </h1>
                <div class="form-group row">
                    <label for="Email1" class="col-sm-3 col-form-label">Email address</label>
                    <input type="email" id="Email1" aria-describedby="emailHelp" class="form-control col-sm-9" placeholder="Enter email" name="email" />
                </div>
                <div class="form-group row">
                    <label for="exampleInputPassword1" class="col-sm-3 col-form-label">Password</label>
                    <input type="password" id="exampleInputEmail1" aria-describedby="emailHelp" class="form-control col-sm-9" placeholder="Password" name="password" />
                </div>
                <div class="form-group row mb-4">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label ml-2" for="exampleCheck1">Remember Me</label>
                </div>
                <div class="form-group row justify-content-center">
                    <button type="submit" class="btn btn-primary col-md-4">Submit</button>
                </div>
                <div class="row justify-content-center col-12" >
                    <a href="#" class="text-primary ">Forget ur password ?</a>
                </div>
                <div class="row justify-content-center col-12" >
                    <a href="signup.php" class="text-primary ">Sign up</a>
                </div>
            </form>
        </div>

<?php
include  $tpl . 'footer.php'; 
?>

