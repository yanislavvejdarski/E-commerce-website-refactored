<?php
namespace controller;
use Model\Address;
use Model\AddressDAO;
use model\CartDAO;
use model\OrderDAO;
use PDOException;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class OrderController{
    public function order()
    {
        UserController::validateForLoggedUser();
        $orderedProducts = new CartDAO();
            if (isset($_POST["order"])){


              $orderedProductsa =  $orderedProducts->showCart($_SESSION["logged_user_id"]);
                OrderDAO::finishOrder($orderedProductsa , $_POST["totalPrice"] , $_SESSION["logged_user_id"]);
                $cart = new CartController;
                $cart->show();


            }

        $msg="Order received!";

    }
    public function show(){
        UserController::validateForLoggedUser();
        
            $products= new OrderDAO();
            $products=$products->showOrders($_SESSION["logged_user_id"]);
            include_once "view/orders.php";


    }
}