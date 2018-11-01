<!--Item page to display products -->
<div class="row">
  <?php
    foreach ($main->getItems() as  $item) {
  ?>
    <div class="col-md-3">
      <div class="card" style="width: 18rem;">
        <div class="card-img-top" style="height:200px;background:url(images/<?=$item['id'];?>.jpg) no-repeat;background-size:cover"></div>
        <div class="card-body">
          <h5 class="card-title"><a href="product.php?id=<?=$item['id'];?>"><?=$item['name']; ?></a></h5>
          <p class="card-text"><?=substr($item['description'],0,50); ?>...</p>
       <form action ='cart.php' method='POST'>
         <input type="hidden" name=id value=<?=$item['id'];?>>
         <input type="hidden" name=qty value="1">
         <input type="hidden" name="csrf" value="<?=$_SESSION['csrf'];?>">
         <input type="submit" class="btn btn-sm btn-primary" value='Add to Basket'>
       </form>
        </div>
      </div>
    </div>
  <?php
    }
  ?>
