<?php
namespace View;
use controller\AddressController;

$addressController=new AddressController();
$cities=$addressController->getCities();


?>


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
    <form action="index.php?target=address&action=add" method="post">
        <div class="form-group">
            <tr>
                <td>City</td>
                <td><select class="form-control" name="city" required>
                        <option value="">Select City</option>
                        <?php foreach ($cities as $city) {

                            ?>
                            <option value=<?=$city["id"]?>><?=$city["name"]?></option>";
                            <?php
                        } ?>
                    </select></td>
            </tr>
            <tr>
                <td>Street name</td>
                <td><input type="text" class="form-control" name="street" placeholder="Enter street name" min="5" required ></td>
            </tr>
            <div class="group-control">
            <tr><td colspan="2"><input type="submit" name="add"class="btn btn-primary mb-2" value="Add new address"></td></tr>
            </div>
        </div>

    </form>
    <a href="index.php?target=user&action=account"><button class="btn btn-primary mb-2">Go Back</button></a>
</div>
