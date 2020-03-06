<?php
namespace view;
use controller\ProductController;




$productController=new ProductController();
$producers=$productController->getProducers();
$types=$productController->getTypes();

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<div class="container">

    <?php
    if (isset($msg) && $msg!="") {
        echo $msg;
    }?> <br>
    <form action="index.php?target=product&action=add" method="post" enctype="multipart/form-data">
        <table>
            <tr>
                <td>Name</td>
                <td><input type="text" name="name" required></td>
            </tr>
            <tr>
                <td>Producer</td>
                <td><select name="producer_id" required>
                        <option value="">Select producer</option>
                        <?php foreach ($producers as $producer) {
                            echo "<option value='$producer->id'>$producer->name</option>";

                        } ?>
                    </select></td>

                </select>
                </td>
            </tr>
            <tr>
                <td>Price</td>
                <td><input type="number" step="0.01" name="price" min="0.01" required></td>

            </tr>

            <tr>
                <td>Type</td>
                <td><select name="type_id" required>
                        <option value="">Select product type</option>
                        <?php foreach ($types as $type) {
                            echo "<option value='$type->id'>$type->name</option>";

                        } ?>
                    </select></td>
            </tr>
            <tr>
                <td>Quantity</td>
                <td><input type="number" name="quantity" min="1" required></td>
            </tr>
            <tr>
                <td>Upload image</td>
                <td><input type="file" name="file" required></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="save" value="Save"></td>
            </tr>
        </table>

    </form>
</div>
</body>
</html>>

