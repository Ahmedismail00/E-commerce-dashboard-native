<?php
ob_start();
session_start();
$noNavbar="";
$PageTitle = 'Login';

if(isset($_SESSION['admin'])){
    header('Location: dashboard.php'); // Redirect to dashboard
}
include "init.php";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username=$_POST['name'];
    $password=$_POST['password'];
    $hashedpass=sha1($password);
    $stmt=$con->prepare("SELECT Username , Password , UserID FROM users WHERE Username = ? AND Password = ? AND GroupID = 0 LIMIT 1 ");
    $stmt->execute(array($username , $hashedpass));
    $row = $stmt->fetch();
    $adminID=$row['UserID'];
    if($stmt->rowCount() > 0 ){
        $_SESSION['admin'] = $username ; // Register Session Name
        $_SESSION['AdminID'] =$adminID; //Register Session ID
        header("location: dashboard.php");
        exit();
    }
}
?>

<div class="col-12 mx-auto">
    <h1 class="text-center">Login Page</h1>
    <form class="login text-center" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <input class="form-control" type="text" name="name" placeholder="Username" autocomplete="off"/>
        <input class="form-control" type="password" name="password" placeholder="Password" autocomplete="new-password"/>
        <input class="btn btn-primary btn-block" type="submit" value="Submit"/>
    </form>
</div>

<?php
include  $tpl . 'footer.php'; 

ob_end_flush();
?>

