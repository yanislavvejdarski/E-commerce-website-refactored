<?php
namespace view;


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
    if(empty($myRatings)){
        echo " <h2>You have no rated product!</h2>";
    }else {


        foreach ($myRatings as $myRating) {
            ?>
            <h2></h2>
            <table>
                <tr>

                    <td><img src="<?= $myRating->image_url ?>" width="150"></td>
                </tr>
                <tr>
                    <td>Product name</td>
                    <td><?= $myRating->product_name; ?></td>
                </tr>
                <tr>
                    <td>My vote</td>
                    <td><?= $myRating->stars ?> stars</td>
                </tr>
                <tr>
                    <td>My comment for this product:</td>
                    <td><?= $myRating->text ?></td>
                </tr>
                <tr>
                    <td>
                        <form action="index.php?target=rating&action=editRatedPage" method="post">
                            <input type="submit" name="editRating" value="Edit">
                            <input type="hidden" name="rating_id" value="<?= $myRating->rating_id ?>">
                            <input type="hidden" name="image_url" value="<?= $myRating->image_url ?>">
                            <input type="hidden" name="product_name" value="<?= $myRating->product_name ?>">
                            <input type="hidden" name="stars" value="<?= $myRating->stars ?>">
                            <input type="hidden" name="text" value="<?= $myRating->text ?>">

                        </form>
                    </td>
                </tr>
            </table>
            <hr>
            <?php
        }
    }?>
</div>
</body>
</html>

