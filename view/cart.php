<?php
namespace view;

use controller\AddressController;
use model\AddressDAO;
use model\FavouriteDAO;
use model\ProductDAO;
?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">



<div class="container">
    <div class="row">
        <div class="col-8">
            <?php
            foreach ($productsInCart as $product) {
                $productDAO = new ProductDAO();
                $productInfo = $productDAO->findProduct($product["product_id"]);
                $totalprice += $product["quantity"] * $productInfo->price;
                ?>
            <div class="card mb-3" style="width: 100%;">

                <div class="row no-gutters">

                    <div class="col-md-4">
                        <a href="index.php?target=product&action=show&prdId=<?=$productInfo->id?>"> <img src="<?=$productInfo->imageUrl?>" class="card-img" alt="..."></a>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?=$productInfo->name?></h5>
                            <div class="row">
                                <form action="index.php?target=cart&action=update" method="post">
                                    <input type="hidden" value="<?= $product["product_id"] ?>" name="productId">
                                    <input type="number" value="<?= $product["quantity"] ?>" name="quantity" min="1" class="form-control" placeholder="Quantity:" aria-label="Input group example" style="width: auto; margin-left: 5px;">
                                    <input type="submit" name="updateQuantity" class="btn btn-primary" style="margin-left: 5px;" value="Update">
                                </form>
                            </div>
                            <h5 class="card-title"><?=$productInfo->price?> Euro</h5>
                            <div class="row">
                                <a class="btn btn-primary" href="index.php?target=cart&action=delete&productId=<?= $product["product_id"] ?>" role="button" style="margin-left: 5px;">Remove From Cart</a>
                                <?php
                                $favoriteDAO=new FavouriteDAO();
                                if ($favoriteDAO->checkIfInFavourites($productInfo->id, $_SESSION["logged_user_id"])) { ?>
                                    <a class="btn btn-primary" href="index.php?target=favourite&action=delete&id=<?= $productInfo->id ?>" role="button" style="margin-left: 5px;">Remove From Favourite</a>
                                <?php } else {
                                ?>
                                <a class="btn btn-primary" href="index.php?target=favourite&action=add&id=<?= $productInfo->id ?>" role="button" style="margin-left: 5px;">Add To Favourite</a>
          <?php
    } ?>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
                <?php
                }
                ?>
        </div>
        <br>
        <div class="col-1">
        </div>

        <div class="col-3">
            <div class="card">
                <div class="card-body">
                    <div class="row" style="width: 100%; margin-top: 5px; margin-left: 0px; margin-right: 0px;">
                        <h2>Delivery information:</h2>
                    </div>
                    <div class="row" style="width: 100%; margin-top: 5px; margin-left: 0px; margin-right: 0px;">
                        <h3>Total price:<?=$totalprice ?> EURO</h3>
                    </div>
<?php
                    $addressDAO=new AddressDAO();
                    $myAddresses = $addressDAO->getAll($_SESSION["logged_user_id"]);

                    $addressController=new AddressController();
                    if ($addressController->checkUserAddress()) {
                    ?>
                    <form action="index.php?action=order&target=order" method="post">
                        <select name="address" required class="form-control">
                            <option value="">Select Address For Delivery</option>
                            <?php foreach ($myAddresses as $address) {
                                $addressDAO = new AddressDAO();
                                $add = $addressDAO->getById($address->id);
                                echo "<option value='$address->id'>$add->city_name , $add->street_name , Bulgaria</option>";
                            }
                            ?>
                        </select>
                        <br>
                        <?php if ($totalprice != 0) { ?>
                        <input type="hidden" value="<?= $totalprice ?>" name="totalPrice">
                        <input type="submit" value="Finish Order" name="order" class="btn btn-outline-primary" style="width: 100%">
                    </form>
                    <?php
                        }
                    } else {?>
                    <div>
                        <h4>You can't finish order without Address!</h4>
                        <a href="index.php?target=address&action=newAddress">
                            <button class="btn btn-outline-secondary" type="button" id="button-addon2">Add Address</button>
                        </a>
                    </div>
                    <?php
                        }?>

                </div>
            </div>
        </div>
    </div>
</div>
