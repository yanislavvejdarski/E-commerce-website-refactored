<?php
namespace view;

use controller\ProductController;

$productController=new ProductController();
$products=$productController->getMostCelledProducts();
?>

    <html lang="en">
<head>
    <link rel="icon" href="icons/favicon.png">
</head>
<meta charset="UTF-8">
<meta name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>eMAG</title>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
      integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<div class="container">
    <h1 class="display-5"> BEST SELLERS ! </h1>
    <div class="row">
        <div class="col-12">
            <div class="row">
                <?php foreach ($products as $product) {
                    ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card">
                            <a href="index.php?target=product&action=show&prdId=<?= $product->id ?>"><img class="card-img-top" src="<?= $product->image_url ?>" height="300" alt="Card image cap"></a>
                            <div class="card-body">
                                <h4 class="card-title"><a href="index.php?target=product&action=show&prdId=<?= $product->id ?>"><?= $product->name ?></a></h4>
                                <div class="row">
                                    <div class="col">
                                        <p class="btn btn-danger btn-block"><?= $product->price ?> Euro</p>
                                    </div>
                                    <div class="col">
                                        <a href="index.php?target=cart&action=add&id=<?= $product->id ?>" class="btn btn-success btn-block">Add to cart</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
