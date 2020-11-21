<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
<div class="container">
  <a class="navbar-brand" href="dashboard.php"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item"><a class="nav-link" href="dashboard.php">Home</a></li>
      <li class="nav-item"><a class="nav-link" href="categories.php?action=manage"><?php echo lang('CATEGORIES'); ?></a></li>
      <li class="nav-item"><a class="nav-link" href="items.php"><?php echo lang('ITEMS'); ?></a></li>
      <li class="nav-item"><a class="nav-link" href="members.php?action=manage"><?php echo lang('MEMBERS'); ?></a></li>
      <li class="nav-item"><a class="nav-link" href="comments.php?action=manage">Comments</a></li>
    </ul>
    <ul class="nav navbar-nav ">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['admin'].' '; ?></a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="../index.php">Visit Shop</a>
          <a class="dropdown-item" href="members.php?action=edit&userID=<?php echo $_SESSION['AdminID'];?>">Edit Profile</a>
          <a class="dropdown-item" href="logout.php">Logout</a>
      </li>
    </ul>
  </div>
  </div>
</nav>
