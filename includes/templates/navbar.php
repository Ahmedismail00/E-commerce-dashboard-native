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
        <?php  
          $stmt=$con->prepare("SELECT Name , ID FROM categories");
          $stmt->execute();
          $row=$stmt->fetchAll();
          foreach($row as $category){?>
            <li class="nav-item"><a class="nav-link" href="category.php?action=<?php echo $category['Name'];?>&ID=<?php echo $category['ID'];?>"><?php echo $category['Name'] ?></a></li>
        <?php }?>
      </ul>
      <?php if(isset($_SESSION['user'])){ ?>
      <ul class="nav navbar-nav ">
        <li class="nav-item dropdown">
          <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php echo $_SESSION['user']?><i class="ml-2 fa fa-angle-double-down"></i>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="profile.php">Edit Profile</a>
            <a class="dropdown-item" href="logout.php">Logout</a>
        </li>
      </ul>

    <?php }else{ ?>
      <ul class="nav navbar-nav ">
        <li class="nav-item dropdown">
          <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-angle-double-down"></i>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="signup.php">Dont have account? Start here</a>
            <a class="dropdown-item" href="index.php">Hello, Sign in</a>

        </li>
      </ul>
    <?php }?>

    </div>
  </div>
</nav>
