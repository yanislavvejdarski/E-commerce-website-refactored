<?php
namespace controller;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use model\CartDAO;
use model\ProductDAO;
use PDOException;
use PDO;
class CartController{
    public function add($params){
        $validateSession = new UserController();
        $validateSession->validateForLoggedUser();

            if (isset($params["addToCart"]) && is_numeric($params["addToCart"])){

                $cartDAO=new CartDAO();
                $productDAO = new ProductDAO();
                $quantity = $productDAO->checkQuantity($params["addToCart"]);
                    $check = $cartDAO->checkIfInCart($params["addToCart"] , $_SESSION["logged_user_id"]);
                    if ($check){
                        if ($check["quantity"] < $quantity["quantity"] && $quantity["quantity"] > 0) {

                            $cartDAO->updateQuantityOfProductInCart($params["addToCart"] , $_SESSION["logged_user_id"]);
                            $this->show();
                        }
                        else{
                            echo "<h1>No more available Pieces</h1>";
                            $this->show();

                        }
                    }
                    elseif($quantity["quantity"] > 0){
                        $cartDAO->putInCart($params["addToCart"] , $_SESSION["logged_user_id"]);
                        $this->show();


                    }
                    else{
                        echo "<h1>Quantity Not Available</h1>";
                        $this->show();
                    }
                }
            else{
                $this->show();
                include_once "view/cart.php";
            }
    }

    public function show(){
        $validateSession = new UserController();
        $validateSession->validateForLoggedUser();
            $cartDAO = new CartDAO();
            $productsInCart = $cartDAO->showCart($_SESSION["logged_user_id"]);
            $totalprice = 0;
            include_once "view/cart.php";

    }
    public function update(){
        $validateSession = new UserController();
        $validateSession->validateForLoggedUser();
        if (isset($_POST["updateQuantity"]) && $_POST["quantity"] > 0 && $_POST["quantity"] < 50
            && is_numeric($_POST["quantity"]) && (round($_POST["quantity"]) == $_POST["quantity"]) ){

                $productDAO=new ProductDAO();
                $productQuantity = $productDAO->checkQuantity($_POST["productId"]);
                if ($productQuantity["quantity"] >= $_POST["quantity"]) {
                    $cartDAO=new CartDAO();
                    $cartDAO->updateCartQuantity($_POST["productId"], $_POST["quantity"] , $_SESSION["logged_user_id"]);
                    $this->show();
                } else {
                    $this->show();
                    echo "<h3>Quantity Not Available</h3>";
                }

        }
        else{
            $this->show();
            include_once "view/cart.php";
        }

    }
    public function delete($params){
        $validateSession = new UserController();
        $validateSession->validateForLoggedUser();

            $cartDAO=new CartDAO();
            $cartDAO->deleteProductFromCart($params["removeFromCart"] , $_SESSION["logged_user_id"]);
            $this->show();

    }
}


