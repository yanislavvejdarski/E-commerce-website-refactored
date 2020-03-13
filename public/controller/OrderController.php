<?php
namespace controller;
use Model\Address;
use Model\AddressDAO;
use model\CartDAO;
use model\OrderDAO;
use PDOException;
use Request;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class OrderController{
    public function order()
    {
        UserController::validateForLoggedUser();
        $request = Request::getInstance();
        $post = $request->postParams();
        $orderedProducts = new CartDAO();
            if (isset($post["order"])){


              $orderedProductsa =  $orderedProducts->showCart($_SESSION["logged_user_id"]);
                OrderDAO::finishOrder($orderedProductsa , $post["totalPrice"] , $_SESSION["logged_user_id"]);
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