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
        $validateSession = new UserController();
        $validateSession->validateForLoggedUser();
        $getParams = $this->request->getParams();
        $sessionParams = $this->session->getSessionParams();
        if (isset($getParams['product']) && is_numeric($getParams['product'])) {
            $cartDAO = new CartDAO();
            $productDAO = new ProductDAO();
            $quantity = $productDAO->checkQuantity($getParams['product']);
            $check = $cartDAO->checkIfInCart(
                $getParams['product'],
                $sessionParams['logged_user_id']
            );
            if ($check) {
                if ($check['quantity'] < $quantity['quantity'] && $quantity['quantity'] > 0) {
                    $cartDAO->updateQuantityOfProductInCart(
                        $getParams['product'],
                        $sessionParams['logged_user_id']
                    );
                    $this->show();
                } else {
                    echo '<h1>No more available Pieces</h1>';
                    $this->show();
                }
            } elseif ($quantity['quantity'] > 0) {
                $cartDAO->putInCart(
                    $getParams['product'],
                    $sessionParams['logged_user_id']
                );
                $this->show();
            } else {
                echo '<h1>Quantity Not Available</h1>';
                $this->show();
            }
        } else {
            $this->show();
            include_once 'view/cart.php';
        }
    }

    /**
     * Show Cart Page
     */
    public function show()
    {
        $validateSession = new UserController();
        $validateSession->validateForLoggedUser();
        $cartDAO = new CartDAO();
        $productsInCart = $cartDAO->showCart($this->session->getSessionParam('logged_user_id'));
        $totalprice = 0;
        include_once 'view/cart.php';
    }

    /**
     * Upgrade Product Quantity In Cart
     */
    public function update()
    {
        $validateSession = new UserController();
        $validateSession->validateForLoggedUser();
        $postParams = $this->request->postParams();
        if (isset($postParams['updateQuantity']) && $postParams['quantity'] > 0 && $postParams['quantity'] < 50
            && is_numeric($postParams['quantity']) && (round($postParams['quantity']) == $postParams['quantity'])) {
            $productDAO = new ProductDAO();
            $productQuantity = $productDAO->checkQuantity($postParams['productId']);
            if ($productQuantity['quantity'] >= $postParams['quantity']) {
                $cartDAO = new CartDAO();
                $cartDAO->updateCartQuantity(
                    $postParams['productId'],
                    $postParams['quantity'],
                    $this->session->getSessionParam('logged_user_id')
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
        $validateSession = new UserController();
        $validateSession->validateForLoggedUser();
        $cartDAO = new CartDAO();
        $cartDAO->deleteProductFromCart(
            $getParams['product'],
            $this->session->getSessionParam('logged_user_id')
        );
        $this->show();
    }
}
