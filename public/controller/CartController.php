<?php
namespace controller;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use model\CartDAO;
use model\ProductDAO;
use PDOException;
use PDO;
use Request;


class CartController{
    public function add(){
        $request = Request::getInstance();
        $params = $request->getParams();
        $validateSession = new UserController();
        $validateSession->validateForLoggedUser();

            if (isset($params["product"]) && is_numeric($params["product"])){

                $cartDAO=new CartDAO();
                $productDAO = new ProductDAO();
                $quantity = $productDAO->checkQuantity($params["product"]);
                    $check = $cartDAO->checkIfInCart($params["product"] , $_SESSION["logged_user_id"]);
                    if ($check){
                        if ($check["quantity"] < $quantity["quantity"] && $quantity["quantity"] > 0) {

                            $cartDAO->updateQuantityOfProductInCart($params["product"] , $_SESSION["logged_user_id"]);
                            $this->show();
                        }
                        else{
                            echo "<h1>No more available Pieces</h1>";
                            $this->show();

                        }
                    }
                    elseif($quantity["quantity"] > 0){
                        $cartDAO->putInCart($params["product"] , $_SESSION["logged_user_id"]);
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
        $request = Request::getInstance();
        $post = $request->postParams();
        if (isset($post["updateQuantity"]) && $post["quantity"] > 0 && $post["quantity"] < 50
            && is_numeric($post["quantity"]) && (round($post["quantity"]) == $post["quantity"]) ){

                $productDAO=new ProductDAO();
                $productQuantity = $productDAO->checkQuantity($post["productId"]);
                if ($productQuantity["quantity"] >= $post["quantity"]) {
                    $cartDAO=new CartDAO();
                    $cartDAO->updateCartQuantity($post["productId"], $post["quantity"] , $_SESSION["logged_user_id"]);
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
    public function delete(){
        $param = Request::getInstance();
        $params = $param->getParams();
        $validateSession = new UserController();
        $validateSession->validateForLoggedUser();

            $cartDAO=new CartDAO();
            $cartDAO->deleteProductFromCart($params["product"] , $_SESSION["logged_user_id"]);
            $this->show();

    }
}


