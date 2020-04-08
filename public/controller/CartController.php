<?php

namespace controller;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use model\DAO\CartDAO;
use model\DAO\ProductDAO;
use helpers\Request;

class CartController extends AbstractController
{
    /**
     * Add Product To Cart
     */
    public function add()
    {
        $getParams = $this->request->getParams();
        $sessionParams = $this->session->getSessionParams();
        $paramsAndRules = [
            $getParams['product'] => 'isVariableSet|isNumeric'
        ];
        if ($this->validator->validate($paramsAndRules)) {
            $cartDAO = new CartDAO();
            $check = $cartDAO->checkIfInCart(
                $getParams['product'],
                $sessionParams['loggedUserId']
            );
            $productDAO = new ProductDAO();
            $quantity = $productDAO->checkQuantity($getParams['product']);
            if ($check) {
                if ($check['quantity'] < $quantity['quantity'] && $quantity['quantity'] > 0) {
                    $cartDAO->updateQuantityOfProductInCart(
                        $getParams['product'],
                        $sessionParams['loggedUserId']
                    );
                    $this->show();
                } else {
                    echo '<h1>No more available Pieces</h1>';
                    $this->show();
                }
            } elseif ($quantity['quantity'] > 0) {
                $cartDAO->putInCart(
                    $getParams['product'],
                    $sessionParams['loggedUserId']
                );
                $this->show();
            } else {
                echo '<h1>Quantity Not Available</h1>';
                $this->show();
            }
        } else {
            $this->show();
        }
    }

    /**
     * Show Cart Page
     */
    public function show()
    {
        $cartDAO = new CartDAO();
        $productsInCart = $cartDAO->showCart($this->session->getSessionParam('loggedUserId'));
        $totalprice = 0;
        include_once 'view/cart.php';
    }

    /**
     * Upgrade Product Quantity In Cart
     */
    public function update()
    {
        $postParams = $this->request->postParams();
        $paramsAndRules = [
            $postParams['updateQuantity'] => 'isVariableSet',
            $postParams['quantity'] => 'biggerThan:0|lessThan:50|isNumeric|roundToSelf'
        ];
        if ($this->validator->validate($paramsAndRules)) {
            $productDAO = new ProductDAO();
            $productQuantity = $productDAO->checkQuantity($postParams['productId']);
            if ($productQuantity['quantity'] >= $postParams['quantity']) {
                $cartDAO = new CartDAO();
                $cartDAO->updateCartQuantity(
                    $postParams['productId'],
                    $postParams['quantity'],
                    $this->session->getSessionParam('loggedUserId')
                );
                $this->show();
            } else {
                $this->show();
                echo '<h3>Quantity Not Available</h3>';
            }
        } else {
            $this->show();
            include_once 'view/cart.php';
        }
    }

    /**
     * Delete Product From Cart
     */
    public function delete()
    {
        $getParams = $this->request->getParams();
        $cartDAO = new CartDAO();
        $cartDAO->deleteProductFromCart(
            $getParams['product'],
            $this->session->getSessionParam('loggedUserId')
        );
        $this->show();
    }
}