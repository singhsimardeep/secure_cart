<?php
  require_once('classes/main.php');
  $main = new main();
?>

<?php include 'includes/header.php';?>
<?php
if (!isset($_SESSION['user'])) {
  header('Location:login.php');
  }
?>

  <div class='container'>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad" >
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">Confirm Order</h3>
                </div>
                <div class="panel-body">
                  <div class="row">
                    <div class=" col-md-9 col-lg-9 ">
                      <table class="table table-user-information">
                        <tbody>
                          <tr>
                            <td>Name</td>
                            <td><?= $_SESSION['userdetails']['firstname']?> <?= $_SESSION['userdetails']['lastname']?></td>
                          </tr>
                          <tr>
                            <td>Order Amount : </td>
                            <td>€ <?=$_POST['amt'];?></td>
                          </tr>
                          <tr>
                            <td>Order Date : </td>
                            <td><?=date("Y/m/d");?></td>
                          </tr>
                          <tr>
                            <td>Address : </td>
                            <td><?= $_SESSION['userdetails']['address']?></td>
                          </tr>
                          <tr>
                          </tr>
                          <tr>
                          </tr>
                          <tr>
                            <td><a type="button" class="btn btn-danger" href="cart.php">
                                  <span class="glyphicon glyphicon-remove"></span> Cancel
                            </a></td>
                            <td><form action ='order.php' method='POST'>
                              <input type="hidden" name='amt' value=<?=$_POST['amt'];?>>
                              <input type="hidden" name="csrf" value="<?=$_SESSION['csrf'];?>">
                              <input id="checkout" class="btn btn-success" type='submit' value='Confirm Order'>
                            </form></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

<?php include 'includes/footer.php';?>
