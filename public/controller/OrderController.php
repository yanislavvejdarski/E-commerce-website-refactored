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
        $postParams = $this->request->postParams();
        $sessionParams = $this->session->getSessionParams();
        $orderedProducts = new CartDAO();
        $paramsAndRules = [
            $postParams['order'] => 'isVariableSet'
        ];
        if ($this->validator->validate($paramsAndRules)) {
            $orderedProducts = $orderedProducts->showCart($sessionParams['loggedUserId']);
            $order = new OrderDAO();
            $order->finishOrder(
                $orderedProducts,
                $postParams['totalPrice'],
                $sessionParams['loggedUserId']
            );
            $cart = new CartController;
            $cart->show();
        }
    }

    /**
     * Show Orders Page
     */
    public function show()
    {
        $products = new OrderDAO();
        $products = $products->showOrders($this->session->getSessionParam('loggedUserId'));
        include_once 'view/orders.php';
    }
}