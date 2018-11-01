<?php
  require_once('classes/main.php');
  $main = new main();
  $items= $main->getBasket();
?>
<?php
// Add to Basket using request parameters
  if (isset($_POST['id'])){
  $main->addCart($_POST['id'],$_POST['qty']);
  header('Location:cart.php');
  }
?>
<?php
// Update basket based on changes
if (isset($_POST['item'])){
    $main->updateBasket ($_POST['item'],$_POST['quantity']);
    header('Location:cart.php');
}
?>
<?php include 'includes/header.php';?>
</script>
    <div class="row">
        <div class="col-sm-12 col-md-10 col-md-offset-1">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Total</th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody>
                  <?php
                      $outofstock=false;
                    foreach ($items as $item) {

                  ?>
                    <tr>
                        <td class="col-sm-8 col-md-6">
                        <div class="media">
                            <a class="thumbnail pull-left" href="#"> <img class="media-object" src="images/product-icon.png" style="width: 72px; height: 72px;"> </a>
                            <div class="media-body">
                                <h4 class="media-heading"><a href="product.php?id=<?=$item['id_items'];?>"><?=$item['name'];?></a></h4>
                                <?php
                                $product=$main->getItem($item['id_items']);
                                if($product[0]['stock']>=$item['qty']){?>
                                  <span>Status: </span><span class="text-success"><strong>In Stock</strong></span>
                                <?php  }
                              else if($product[0]['stock']<$item['qty']){
                                if($outofstock == false){
                                  $outofstock = true;
                                } ?>
                                <span>Status: </span><span id="stockid" class="text-danger"><strong>Out of Stock</strong></span>
                            <?php   }  ?>
                            </div>
                        </div></td>
                        <td class="col-sm-1 col-md-1" style="text-align: center">
                          <form action ='cart.php' method='POST'>
                            <input type="hidden" name=item value=<?=$item['id_items'];?>>
                            <input type="text" name=quantity value="<?=$item['qty'];?>" onchange="this.form.submit()" onkeyup="validate(<?=$outofstock;?>)">
                            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf'];?>">
                          </form>
                        </td>
                        <td class="col-sm-1 col-md-1 text-center"><strong>€<?=$item['ttl'];?></strong></td>
                        <td class="col-sm-1 col-md-1 text-center"><strong>€<?=$item['ttl']*$item['qty'];?></strong></td>
                        <td class="col-sm-1 col-md-1">
                          <form action ='cart.php' method='POST'>
                            <input type="hidden" name=item value=<?=$item['id_items'];?>>
                            <input type="hidden" name=quantity value=0>
                            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf'];?>">
                            <input type="submit" class="btn btn-danger" value='Remove'>
                        </form>

                      </td>
                    </tr>
                        <?php } ?>
                </tbody>

                <tfoot>
                    <tr>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td><h3>Total</h3></td>
                        <?php
                          $amt=0;
                          foreach ($items as $item) {
                            $amt = $amt + $item['ttl']*$item['qty'];
                          } ?>

                        <td class="text-right"><h3>€<?=$amt;?></h3></td>
                    </tr>
                    <tr>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td>
                        <a class="btn btn-info" href="index.php">
                            <span class="glyphicon glyphicon-shopping-cart"></span> Continue Shopping
                        </a></td>
                        <td>
                          <?php if($amt>0 && !$outofstock){ ?>
                            <form action ='confirm-order.php' method='POST'>
                              <input type="hidden" name='amt' value=<?=$amt;?>>
                              <input type="hidden" name="csrf" value="<?=$_SESSION['csrf'];?>">
                              <input id="checkout" class="btn btn-success" type='submit' value='Checkout'>

                          </form>
                      <?php } ?>
                      </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?php include 'includes/footer.php';?>
