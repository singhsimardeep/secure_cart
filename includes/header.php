<!-- Header to be included in all files -->
<?php
// Sanitize post and get requests
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

// Logout
if (isset($_GET['logout'])) {
  session_destroy();
  session_regenerate_id();
  header('Location:index.php');
  }
?>
<!-- CSRF Implementation. All POST requests will be verified for a valid csrf -->
<?php
if(isset($_POST) && count($_POST)>=1 && !isset($_POST['csrf'])){
  echo ("<script type='text/javascript'>alert('Invalid Attempt !!');</script>");
      header('location:index.php');

      exit;
}
else if(isset($_POST) && count($_POST)>=1 && $_POST['csrf'] !== $_SESSION['csrf']){
  echo ("<script type='text/javascript'>alert('Invalid Attempt !!');</script>");
    header('location:index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>My Shop</title>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="vendor/js/bootstrap.min.js"></script>
    <script src="vendor/jquery/jquery.min.js"></script>
    <link href="css/shop-item.css" rel="stylesheet">

  </head>

  <body>
<!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="index.php">My Shop</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
              <a class="nav-link" href="index.php">Home
              </a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="cart.php">My Basket
              </a>
            </li>
            <?php if(!isset($_SESSION['user'])){ ?>
            <li class="nav-item active">
              <a class="nav-link" name="login" class="nav-link" href="login.php">Login</a>
            </li>
          <?php }else{ ?>
            <li class="nav-item active">
              <a name="Orders" class="nav-link" href="order.php">Orders</a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" name="logout" class="nav-link" href="index.php?logout=true">Logout</a>
            </li>

          <?php } ?>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container">
