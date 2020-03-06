<?php
namespace view;
use controller\AddressController;


$addressController=new AddressController();
$cities=$addressController->getCities();


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
    if (isset($msg) && $msg!=""){
        ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $msg;?>
        </div>
        <?php
    }
    ?>
    <form action="index.php?target=address&action=edit" method="post" class="form-group">
        <input class="form-group" type="hidden" name="address_id" value="<?php echo $address->id ?>">
        <table>
            <tr>
                <td>City</td>
                <td><select class="form-group" name="city" required>
                        <option value="<?php echo $address->city_id ?>"><?php echo $address->city_name ?></option>
                        <?php foreach ($cities as $city) {

                            ?>
                            <option value=<?=$city["id"]?>><?=$city["name"]?></option>";
                            <?php
                        } ?>
                    </select></td>
            </tr>
            <tr>
                <td>Street name</td>
                <td><input type="text" name="street" value="<?php echo $address->street_name?>" placeholder="Enter street name" min="5" required ></td>
            </tr>
            <tr><td colspan="2"><input type="submit" class="btn btn-primary mb-2" name="save" value="Save changes"></td></tr>
        </table>
    </form>
    <a href="index.php?target=user&action=account"><button class="btn btn-primary mb-2">Back</button></a>
</div>
</body>
</html>
