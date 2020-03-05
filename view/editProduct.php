<?php
namespace view;
use controller\ProductController;


if (isset($msg) && $msg!="") {
    echo $msg;
}



?> <br>

<?php

$productController=new ProductController();
$producers=$productController->getProducers();
$product=$productController->getProductById($productId);
$types= $productController->getTypes();

$isInPromotion=false;
if ($product["old_price"]!=NULL) {
    $isInPromotion=true;
    echo "In Promotion!";
}?> <br>


<table>
    <tr>
        <td><?= $product["name"]?></td>
    </tr>
    <tr>
        <td><img src="<?=$product["image_url"] ?>" width="150"></td>
    </tr>
    <tr>
        <td>Producer:</td>
        <td><?=$product["producer_name"] ?></td>
    </tr>
    <tr>
        <td>Type:</td>
        <td><?= $product["type_name"] ?></td>
    </tr>
    <tr>
        <td>Quantity:</td>
        <td><?= $product["quantity"] ?></td>
    </tr>

    <?php if($isInPromotion){
        ?>
        <tr>
            <td>Old Price:</td>
            <td><?= $product["old_price"]?> EURO</td>
        </tr>
        <tr>
            <td>New Price:</td>
            <td><?=  $product["price"]?> EURO</td>
        </tr>

        <?php
    }else{
        ?>
        <tr>
            <td>Price:</td>
            <td><?= $product["price"] ?> EURO</td>
        </tr>
        <?php
    }?>

</table>
<hr>

<h3>Edit this product:</h3>
<form action="index.php?target=product&action=edit" method="post" enctype="multipart/form-data">
    <table>
        <tr>
            <td>Name</td>
            <td><input type="text" name="name" value="<?= $product["name"]?>" required></td>
            <td><input type="hidden" name="product_id" value="<?= $productId?>"></td>


        </tr>
        <tr>
            <td>Producer</td>
            <td>
                <select name="producer_id" required>
                    <option  value="<?= $product["producer_id"]?>"><?= $product["producer_name"]?></option>
                    <?php foreach ($producers as $producer) {
                        echo "<option value='$producer->id'>$producer->name</option>";
                    } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Type</td>
            <td>
                <select name="type_id" required>
                    <option value="<?= $product["type_id"]?>"><?= $product["type_name"]?></option>
                    <?php foreach ($types as $type) {
                        echo "<option value='$type->id'>$type->name</option>";

                    } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Quantity</td>
            <td><input type="number" name="quantity" min="0" value="<?= $product["quantity"]?>" required></td>

        </tr>
        <tr>
            <td>Price</td>
            <td><input type="number" step="0.01" name="price" min="0.01" value="<?= $product["price"]?>" required></td>
        </tr>

        <tr>
            <td>Get in promotion</td>
            <td><input type="number" step="0.01" name="newPrice" min="0.01" placeholder="Get new price here"></td>

        </tr>

        <tr>
            <td>Upload image</td>
            <td><input type="file" name="file"></td>
        <tr><input type="hidden" name="old_image" value="<?= $product["image_url"]?>"></tr>
        </tr>


        <tr>
            <td colspan="2"><input type="submit" name="saveChanges" value="Save"></td>
        </tr>
    </table>
</form>

<form action="index.php?target=product&action=removeDiscount" method="post">
    <input type="hidden" name="product_id" value="<?= $productId?>">
    <input type="hidden" name="product_old_price" value="<?=$product["old_price"]?>">
    <input type="submit" name="remove" value="Remove Promotion">
</form>
