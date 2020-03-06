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
<form action="index.php?target=rating&action=editRate" method="post">
    <table>
        <tr> <td><?= $_POST['product_name'];?></td></tr>
        <tr><td><img src="<?= $_POST['image_url'] ?>"width="150"></td></tr>
        <tr><input type="hidden" name="rating_id" value="<?= $_POST['rating_id'] ?>"></tr>
        <tr>
            <td>Give your vote from 1 to 5 for this product</td>
            <td><input type="number" name="rating" min="1" max="5" value="<?= $_POST['stars'] ?>"></td>
        </tr>
        <tr>
            <td>Write comments for this product</td>
            <td><textarea name="comment" id="" cols="30" rows="10" minlength="4" ><?= $_POST['text'] ?></textarea></td>
        </tr>

        <tr>
            <td colspan="2"><input type="submit" name="saveChanges" value="Save"></td>
        </tr>
    </table>
</form>
</body>
</html>
