<?php
namespace view;
use controller\UserController;


$userController=new UserController();
$user=$userController->getUserById();





?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<div class="container">
    <?php
    if (isset($msg) && $msg!=''&& $msg!="success"){
        ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $msg;?>
        </div>
        <?php
    }elseif(isset($msg) &&  $msg=="success"){
        ?>
        <div class="list-group-item list-group-item-success" role="alert">
            Successfully updated account!
        </div>
        <?php
    }?>
    <form action="index.php?target=User&action=edit" method="post">


        <div class="form-group">
            <label for="exampleInputEmail1">Email address:</label>
            <label for="exampleInputEmail1"><?php echo $user->email ?></label>

            <input name="email" type="hidden" value="<?php echo $user->email ?>" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" >

        </div>

        <div class="form-group">
            <label for="exampleInputEmail1">First Name</label>
            <input type="text" name="first_name" value="<?php echo $user->first_name ?>" class="form-control" >

        </div>
        <div class="form-group">
            <label >Last Name</label>
            <input type="text" name="last_name" value="<?php echo $user->last_name ?>" class="form-control" >

        </div>
        <div class="form-group">
            <label >Age</label>
            <input type="number" name="age" value="<?php echo $user->age ?>" class="form-control" min="0" max="100" >

        </div>
        <div class="form-group">
            <label >Phone number</label>
            +359 <input type="number" name="phone_number" value="<?php echo $user->phone_number ?>" class="form-control" placeholder="8**-***-***" >

        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Change Password</label>
            <input name="newPassword" type="password" class="form-control" id="exampleInputPassword1" placeholder="Enter your new password" >
        </div>

        <?php
        if($user->subscription==="yes"){
            ?>

            <div class="form-group">
                <label >Subscribe for notification about latest promotions:</label><br>
                <input type="radio" name="subs" value="yes" checked> YES <br>
                <input type="radio" name="subs" value="no">NO <br>
            </div>
            <?php
        }else{
            ?>
            <div class="form-group">
                <label >Subscribe for notification about latest promotions:</label>
                <input type="checkbox" name="subscription">

            </div>

            <?php
        }

        ?>
        <div class="form-group">
            <label for="exampleInputPassword1">Enter password to confirm changes</label>
            <input name="accountPassword" type="password" class="form-control" id="exampleInputPassword1"  >
        </div>



        <button name="edit" type="submit" class="btn btn-primary">Save changes</button>
    </form>
    <a href="index.php?target=User&action=account"><button name="myAccount" class="btn btn-primary">Back to My Account</button></a>
</div>
</body>
</html>
