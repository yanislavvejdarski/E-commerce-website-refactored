<?php
namespace controller;
use exception\BadRequestException;
use exception\NotAuthorizedException;
use model\ProductDAO;
use model\RatingDAO;


class ratingController
{


    public function rate(){
        UserController::validateForLoggedUser();
        $msg="";
        if (isset($_POST["save"])) {

            if (empty($_POST["comment"]) || empty($_POST["rating"])) {
                $msg = "All fields are required!";
            }elseif($this->commentValidation($_POST["comment"])){
                $msg = "Invalid comment!";
            }elseif($this->ratingValidation($_POST["rating"])){
                $msg = "Invalid rating!";
            }

            $productDAO=new ProductDAO();
            if($productDAO->findProduct([$_POST["product_id"]])){
                if ($msg == "") {

                    $ratingDAO=new RatingDAO();
                    $ratingDAO->addRating($_SESSION["logged_user_id"], $_POST["product_id"], $_POST["rating"], $_POST["comment"]);
                    header("Location: index.php?target=product&action=show&prdId=".$_POST["product_id"]);

                }else{
                    throw new BadRequestException("$msg");
                }
            }else{
                throw new NotAuthorizedException("Not authorized for this operation!");
            }

        }

    }

    public function editRate(){
        $msg="";
        UserController::validateForLoggedUser();
        if (isset($_POST["saveChanges"])) {

            if (empty($_POST["comment"]) || empty($_POST["rating"])) {
                $msg = "All fields are required!";
            }elseif($this->commentValidation($_POST["comment"])){
                $msg = "Invalid comment!";
            }elseif($this->ratingValidation($_POST["rating"])){
                $msg = "Invalid rating!";
            }

              $ratingDAO=new RatingDAO();
            $rating=$ratingDAO->getRatingById($_POST["rating_id"]);
            if($rating->user_id!==$_SESSION["logged_user_id"]){

                throw new NotAuthorizedException("Not authorized for this operation!");
            }elseif($msg == "") {
               $ratingDAO=new RatingDAO();
                $ratingDAO->editRating($_POST["rating_id"], $_POST["rating"], $_POST["comment"]);
                header("Location: index.php?target=rating&action=myRated");

            }
        }else{
            throw new BadRequestException("$msg");

        }
    }

    public function showStars($product_id){


        $ratingDAO=new RatingDAO();
        $product_stars=$ratingDAO->getStarsCount($product_id);


        $starsCountArr = [];
        for ($i = 1; $i <= 5; $i++) {
            $isZero = true;
            foreach ($product_stars as $product_star) {
                if ($product_star["stars"] == $i) {
                    $starsCountArr[$i] = $product_star["stars_count"];
                    $isZero = false;
                }
            }
            if ($isZero) {
                $starsCountArr[$i] = 0;
            }
        }

        return $starsCountArr;
    }



    public function commentValidation($comment){
        $err=false;
        if (strlen($comment) < 4 || strlen($comment)>200) {
            $err=true;
        }
        return $err;
    }

    public function ratingValidation($rating){
        $err=false;
        if (!is_numeric($rating) || !preg_match('/^[1-5]+$/', $rating)) {
            $err=true;
        }
        return $err;
    }

    public function myRated(){
        UserController::validateForLoggedUser();
        $ratingDAO=new RatingDAO();
        $myRatings=$ratingDAO::showMyRated($_SESSION["logged_user_id"]);
        include_once "view/myRated.php";
    }

    public function rateProduct()
    {
        UserController::validateForLoggedUser();

        include_once "view/rateProduct.php";
    }

    public function editRatedPage()
    {
        UserController::validateForLoggedUser();

        include_once "view/editRatedProduct.php";
    }
}