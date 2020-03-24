<?php

namespace controller;

use model\DAO\CartDAO;
use model\DAO\OrderDAO;
use helpers\Request;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class OrderController extends AbstractController
{
    /**
     *  Finish Order
     */
    public function order()
    {
        UserController::validateForLoggedUser();
        $postParams = $this->request->postParams();
        $sessionParams = $this->session->getSessionParams();
        $orderedProducts = new CartDAO();
        if (isset($postParams['order'])) {
            $orderedProductsa = $orderedProducts->showCart($sessionParams['logged_user_id']);
            $order = new OrderDAO();
            $order->finishOrder(
                $orderedProductsa,
                $postParams['totalPrice'],
                $sessionParams['logged_user_id']
            );
            $cart = new CartController;
            $cart->show();
        }
        $msg = 'Order received!';
    }

    /**
     * Show Orders Page
     */
    public function show()
    {
        UserController::validateForLoggedUser();
        $products = new OrderDAO();
        $products = $products->showOrders($this->session->getSessionParam('logged_user_id'));
        include_once 'view/orders.php';
    }
}