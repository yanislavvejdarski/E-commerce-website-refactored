<?php
namespace view;


?>
<div class="container">
    <?php
    foreach ($products as $product){
        ?>

        <table class="form-group">
            <tr>
                <td width="80"><?=$product["name"]?></td>
                <td><img src="<?=$product["image_url"]?>" width="150"></td>
                <td><?="Quantity : ". $product["quantity"]." "?></td>
                <td><?= "  | Price ".$product["productPrice"] .  " euro |"?></td>
                <td><?= "Date of order : ".substr($product["date_created"]  , 0 , 10) ."|"?></td>
                <td>Time of order <?=substr($product["date_created"]  , 10 , 10) ?></td>
            </tr>
        </table>
        <hr style="border-top: black solid 4px">
        <?php
    }

    ?>
</div>


