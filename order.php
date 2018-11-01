
<?php
  require_once('classes/main.php');
  $main = new main();
?>
<?php
if (!isset($_SESSION['user'])) {
  header('Location:login.php');
  }
?>
<?php include 'includes/header.php';?>
<!-- Add Order to tbl_orders-->
<?php
if (isset($_POST['amt'])){
    $main->addOrder($_POST['amt']);
}

  $items= $main->getOrders();
?>
<div class="container">
	<div class="row">
    <div class="table-responsive">
      <h2>My Orders</h2>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Order Date</th>
            <th>Amount</th>
            <th>Status</th>

          </tr>
        </thead>
        <tbody>
          <?php
            foreach ($items as $item) {
          ?>
          <tr>
            <td><?=$item['id'];?></td>
            <td><?=$item['orderdate'];?></td>
            <td>€ <?=$item['amountprice'];?></td>
            <td><span class="label label-success">Completed</span></td>
          </tr>
        <?php } ?>
        </tbody>
      </table>

    </div>
  </div>
</div>
<?php include 'includes/footer.php';?>
