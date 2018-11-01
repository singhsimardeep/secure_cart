<?php
  require_once('classes/main.php');
  $main = new main();
?>
<?php include 'includes/header.php';?>
<script>
    $(document).ready(function(){
        var quantity = 1;

        $('.quantity-right-plus').click(function(e){
            e.preventDefault();
            var quantity = parseInt($('#quantity').val());
            $('#quantity').val(quantity + 1);
        });

        $('.quantity-left-minus').click(function(e){
            e.preventDefault();
            var quantity = parseInt($('#quantity').val());
            if(quantity > 1){
                $('#quantity').val(quantity - 1);
            }
        });

    });
</script>
    <div class="row">
        <?php
          foreach ($main->getProducts($_GET['id']) as  $item) {
        ?>

        <div class="col-12 col-lg-6">
                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <a href="" data-toggle="modal" data-target="#productModal">
                                <img class="img-fluid" src="images/<?=$item['id'];?>.jpg" />
                                <p class="text-center">Zoom</p>
                            </a>
                        </div>
                    </div>
                </div>

        <div class="col-12 col-lg-6 add_to_cart_block">
            <div class="card bg-light mb-3">
                <div class="card-body">
                  <h2>  <p class="price">Price: € <?=$item['price'];?></p></h2>
                    <form method="post" action="cart.php">

                        <div class="form-group">
                            <label>Quantity :</label>
                            <div class="input-group mb-3">
                                <input type="hidden" name="id" value='<?=$item['id'];?>'>
                                <input type="number" min=1 max=<?=$item['stock'];?> name="qty" value="1">
                            </div>
                            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf'];?>">
                        </div>
                        <input type="button" value ="Add To Cart" class="btn btn-success btn-lg btn-block text-uppercase" onclick="this.form.submit()">
                    </form>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Description -->
        <div class="col-12">
            <div class="card border-light mb-3">
                <div class="card-header bg-primary text-white text-uppercase"><i class="fa fa-align-justify"></i> Description</div>
                <div class="card-body">
                  <p class="description"><?=$item['description'];?></p>
                </div>
            </div>
        </div>

<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Product title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <img class="img-fluid" src="images/<?=$item['id'];?>.jpg" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
      </div>
    </div>
<?php
}
?>
</div>
<?php include 'includes/footer.php';?>
