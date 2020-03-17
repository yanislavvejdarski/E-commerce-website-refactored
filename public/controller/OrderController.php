<?php
namespace controller;
use model\CartDAO;
use model\OrderDAO;
use Request;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class OrderController extends AbstractController {
    public function order()
    {
        UserController::validateForLoggedUser();
        $postParams = $this->request->postParams();
        $orderedProducts = new CartDAO();
            if (isset($postParams["order"])){


              $orderedProductsa =  $orderedProducts->showCart($_SESSION["logged_user_id"]);
                OrderDAO::finishOrder($orderedProductsa , $postParams["totalPrice"] , $_SESSION["logged_user_id"]);
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