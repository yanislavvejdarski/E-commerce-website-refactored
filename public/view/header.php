<?php
namespace view;

use model\TypeDAO;

$typeDAO=new TypeDAO();
$categories=$typeDAO->getCategories();
$types=$typeDAO->getTypes();


?>


<div class="bg-light" >
<nav class="navbar navbar-expand-lg navbar-light bg-light container" >
    <a href="index.php?target=main&action=render"><img src="icons/emagLogo.svg" height="100" width="150"></a>

    <ul class="navbar-nav mr-auto " style="margin-right: 0px !important;">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-left: 5px;">
                <i class="fa fa-bars fa-3x" aria-hidden="true"></i>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">

                <?php foreach ($categories as $category) {
                    ?>
                    <li class='dropdown-submenu'><a class='dropdown-item dropdown-toggle' data-toggle='dropdown' href='index.php?target=product&action=show&ctgId=<?=$category->id?>'><?=$category->name?></a>
                        <ul class='dropdown-menu'>
                            <?php
                            foreach ( $types as $type) {
                                if($type->categorie_id==$category->id){
                                    ?>
                                    <a class='dropdown-item' href='index.php?target=product&action=show&typId=<?=$type->id?>'><?=$type->name?></a>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                    </li>
                    <?php
                }?>

            </ul>
        </li>
    </ul>

    <?php
    include_once "view/search.php";

    ?>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


        <a href="index.php?target=user&action=account">	<img src="icons/user.svg" href="" height="60" width="60"></a>
        <div class="dropdown">
            <a class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                My Account
            </a>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <?php if(isset( $_SESSION["logged_user_id"])){
                    ?>
                    <p>Hello,<?= $_SESSION["logged_user_first_name"] . " " . $_SESSION["logged_user_last_name"]  ?> </p>
                    <a class="dropdown-item" href="index.php?target=user&action=account">My Account</a>
                    <a class="dropdown-item" href="index.php?target=order&action=show">My Orders</a>
                    <a class="dropdown-item" href="index.php?target=address&action=newAddress">Add Address</a>
                    <a class="dropdown-item" href="index.php?target=rating&action=myRated">My Rated Products</a>
                    <hr>
                    <a class="dropdown-item" href="index.php?target=user&action=logout">Log Out</a>
                    <?php
                }else{
                    ?>

                <a class="dropdown-item" href="index.php?target=user&action=loginPage">Login</a>
                    <hr>
                <a class="dropdown-item" href="index.php?target=user&action=registerPage">Register</a>

                <?php
                }
                ?>
            </div>
        </div>
        <?php

    if(isset($_SESSION["logged_user_role"]) && $_SESSION["logged_user_role"]=="admin"){
        ?>
        <a href="index.php?target=product&action=addProduct"><button>Add New Product</button></a>
        <?php
    }else{
        ?>
        <a  href="index.php?target=favourite&action=show"><img src="icons/like.svg" height="60" width="60">Favourites</a>
        <a href="index.php?target=cart&action=show"><img src="icons/cart.svg" height="60" width="60">Мy Cart</a>
        <?php
    }
    ?>



    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    </div>
</nav>
</div>
<link rel="stylesheet" href="view/css.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>




