<?php
namespace view;

use model\ProductDAO;

    ?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <div class="container">
    <h2>My Favourite Products</h2>
    <div class="row">
        <div class="col-12">

            <div class="row">
                <?php
                foreach ($favourites as $favourite) {
                    $productDAO=new ProductDAO();
                    $product = $productDAO->findProduct($favourite["product_id"])
                    ?>

                    <div class="col-3">

                        <div class="card">
                           <a href="index.php?&target=product&action=show&prdId=<?=$product->id?>"> <img class="card-img-top" src="<?= $product->imageUrl ?>" alt="Card image cap" height="200" width="30"></a>
                            <div class="card-body">
                                <h5 class="card-title"><?= $product->name ?></h5>
                                <p class="card-text"><?= $product->price ?> EURO</p>
                                <a href="index.php?target=cart&action=add&id=<?= $product->id ?>"
                                   class="btn btn-primary">Add to cart</a>
                                <a href="index.php?target=favourite&action=delete&id=<?= $product->id ?>"
                                   class="btn btn-primary">Remove From Favourite</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
