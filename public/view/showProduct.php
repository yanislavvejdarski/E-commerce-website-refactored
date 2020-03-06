<?php
namespace view;
use controller\RatingController;
use model\FavouriteDAO;
use controller\ProductController;
use model\RatingDAO;



    $ratingDAO=new RatingDAO();
    $review=$ratingDAO->getReviewsNumber($this->id);
    $comments=$ratingDAO->getComments($this->id);

    $ratingController=new RatingController();
    $countOfStars=$ratingController->showStars($this->id);

    $productController=new ProductController();
    $status=$productController->checkIfIsInPromotion($this->id);
    $productAttributes=$productController->getAttributes($this->id);

    if (isset($_GET["v"]))
    {
        if ($_GET["v"] == 1) {
            $msg = "Not Available";
        }
        elseif($_GET["v"] == 0){
            $msg = "Added 1 To Cart !";
        }
    }
    if (isset($_GET["v"])){
       echo "<h1 >$msg</h1>";
        }
    ?>

    <div  class="container">


        <div class="row">
            <h3><?= $this->name ?></h3>
        </div>
        <div class="row">
            <div class="col">
                <img src="<?= $this->imageUrl ?>" width="300" height="300" class="">
            </div>
            <div class="col">
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <?= $review->reviews_count ?> reviews
                        </div>

                        <div class="row">
                            <?= $status["is_in_stock"] ?>
                        </div>
                        <div class="row">
<table>
    <?php if($status["in_promotion"]){
        ?>
        <tr>
            <td>Old Price:</td>
            <td><?=$status["old_price"] ?> EURO</td>
        </tr>
        <tr>
            <td>New Price:</td>
            <td><?= $this->price ?> EURO</td>
        </tr>
        <tr>
            <td>Discount:</td>
            <td><?= $status["discount"] ?> %</td>
        </tr>
        <?php
    }else{
        ?>
        <tr>
            <td>Price:</td>
            <td><?= $this->price ?> EURO</td>
        </tr>
        <?php
    }?>
</table>
                        </div>
                        <div class="row">
 <?php if(isset($_SESSION["logged_user_role"]) && $_SESSION["logged_user_role"]=="admin"){?>

                <form action="index.php?target=product&action=editProduct" method="post">
                    <input type="hidden" name="product_id" value="<?= $this->id ?>">
                    <input type="submit" name="editProduct" value="Edit this product">
                </form>

        <?php }?>
                        </div>
                        <div class="row">
                            <?php if (isset($_SESSION["logged_user_role"])){
                                ?>

                                    <a href="index.php?target=cart&action=add&id=<?=$this->id?>" class="btn btn-primary btn-lg btn-block">Add To Cart </a>

                                <?php
                                $favouriteDAO=new FavouriteDAO;
                                $checker = $favouriteDAO->checkIfInFavourites($this->id , $_SESSION["logged_user_id"]);

                                ?>

                                <a href="index.php?target=rating&action=rateProduct&id=<?=$this->id?>" class="btn btn-primary btn-lg btn-block">Rate This Product</a>

                                <?php

                                if ($checker)
                                {
                                    ?>
                                        <form action="index.php?target=favourite&action=delete&id=<?=$this->id?>" method="post">
                                            <input type="hidden" value="<?=$this->id?>" name="like">
                                            <button type="submit"><img src="icons/like.svg" width="50" height="50"></button>
                                        </form>
                                    <?php
                                }
                                else{

                                    ?>
                                            <form action="index.php?target=favourite&action=add&id=<?=$this->id?>" method="post">
                                                <input type="hidden" value="<?=$this->id?>" name="like">
                                                <button type="submit"><img src="icons/unlike.svg" width="50" height="50"></button>
                                            </form>
                                    <?php
                                }

                            }
                            else {
                                ?>

                                    <a href="index.php?target=user&action=loginPage" class="btn btn-primary btn-lg btn-block">Add To Cart</a>

                                <a href="index.php?target=user&action=loginPage" class="btn btn-primary btn-lg btn-block">Rate This Product</a>
                                <form action="index.php?target=user&action=loginPage" method="post">
                                    <button type="submit"><img src="icons/unlike.svg" width="50" height="50"></button>
                                </form>


                                <?php
                            }
                            ?>
                        </div>

                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <h3>Characteristics:</h3>
        </div>

        <?php foreach ($productAttributes as $productAttribute) {
            ?>
            <div class="row">
                <h5><?= $productAttribute->name?>: <?= $productAttribute->value?></h5>
            </div>
            <?php
        }?>

    </div>
    <br>
    <br>

<div class="container">
    <div class="row">
        <div class="col">
            <div class="row">
                <h2>Average grade: <?= $review->avg_stars?></h2>
            </div>
            <div class="row">
                <div class="col">
                    <?php foreach ($countOfStars as $key=>$countOfStar) {
                        ?>
                        <div class="row">
                            <h3> Rate with <?= $key?> stars: <?= $countOfStar?></h3>

                        </div>
                        <?php
                    }
                    ?>
                </div>

            </div>
        </div>
    </div>
</div>

    <br><br>
    <div class="container">

        <div class="row">
            <h1>Comments:</h1>
        </div>
    <?php foreach ($comments as $comment) {
        ?>
        <div class="row">

                <div class="col">
                    <div class="row">
                        <h3><?= $comment->full_name ?></h3>
                    </div>
                    <div class="row">
                        <h3><?= $comment->date ?></h3>
                    </div>
                </div>
                <div class="col">
                    <div class="row">
                        <h3>Rating: <?= $comment->stars ?> stars</h3>
                    </div>
                    <div class="row">
                        <h3><?= $comment->text ?></h3>
                    </div>
                </div>
        </div>
        <hr>
    <?php
} ?>
    </div>


