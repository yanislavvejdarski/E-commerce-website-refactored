<?php
namespace view;


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
          crossorigin="anonymous">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<div class="container">
    <div class="row">
        <div class="col-3">
            <div class="card">
                <div class="card-body">
                    <div class="row" style="width: 100%; margin-top: 5px; margin-left: 0px; margin-right: 0px;">
                        <h3> <?= $_SESSION["logged_user_first_name"] . " " .  $_SESSION["logged_user_last_name"]?></h3>
                    </div>
                    <hr>
                    <div class="row" style="width: 100%; margin-top: 5px; margin-left: 0px; margin-right: 0px;">
                        <a href="index.php?target=order&action=show" style="width: 100%">
                            <button type="button"  class="btn btn-outline-primary" style="width: 100%">My orders</button>
                        </a>
                    </div>
                    <div class="row" style="width: 100%; margin-top: 5px; margin-left: 0px; margin-right: 0px;">
                        <a href="index.php?target=rating&action=myRated" style="width: 100%">
                            <button type="button"  class="btn btn-outline-primary" style="width: 100%">My rated products</button>
                        </a>
                    </div>
                    <div class="row" style="width: 100%; margin-top: 5px; margin-left: 0px; margin-right: 0px;">
                        <a href="index.php?target=address&action=newAddress" style="width: 100%">
                            <button type="button"  class="btn btn-outline-primary" style="width: 100%">Add Address</button>
                        </a>
                    </div>
                    <div class="row" style="width: 100%; margin-top: 5px; margin-left: 0px; margin-right: 0px;">
                        <a href="index.php?target=user&action=logout" style="width: 100%">
                            <button type="button"  class="btn btn-outline-primary" style="width: 100%">Log Out</button>
                        </a>
                    </div>


                </div>
            </div>
        </div>

        <div class="col-1">
        </div>

        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    About Me
                </div>
                <div class="card-body">
                    <p class="card-text"> First Name: <?php echo $user->first_name ?> </p>
                    <p class="card-text"> Last Name: <?php echo $user->last_name ?></p>
                    <p class="card-text"> Email: <?php echo $user->email; ?></p>
                    <p class="card-text">  Age: <?php echo $user->age ?></p>
                    <p class="card-text">  Phone Number: <?php echo "+359". $user->phone_number ?></p>
                    <a href="index.php?target=User&action=editPage" class="btn btn-primary">Edit Profile</a>
                </div>
            </div>



            <div class="card" style="margin-top: 10px;">
                <div class="card-header">
                    My addresses:
                </div>
                <div class="card-body">
                    <?php foreach ($addresses as $address) {
                        ?>
                        <div class="card w-75" style="margin-bottom: 10px;">
                            <div class="card-body">
                                <p class="card-text"><?php echo $address->street_name . ', ' . $address->city_name; ?></p>
                                <div class="row">
                                    <form action='index.php?target=address&action=editAddress' method="post" style="margin-left: 15px;">
                                        <input type='hidden' name='address_id' value="<?php echo $address->id; ?>">
                                        <input type="submit" class="btn btn-primary" name="editAddress" value="Edit" >
                                    </form>

                                    <form action='index.php?target=address&action=delete' method="post" style="margin-left: 15px;">
                                        <input type='hidden' name='address_id' value="<?php echo $address->id; ?>">
                                        <input type="submit" class="btn btn-primary" name="deleteAddress" value="Delete">
                                    </form>

                                </div>
                            </div>
                        </div>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
