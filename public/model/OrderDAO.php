<?php

namespace model;

use model\CartDAO;
use model\ProductDAO;
use PDO;
use PDOException;

class OrderDAO extends AbstractDAO
{
    /**
     * @param $orderId int
     * @param $orderedProducts array
     */
    function addOrderProducts(
        $orderId,
        $orderedProducts
    ) {
        foreach ($orderedProducts as $product) {
            $params = [];
            $params[] = $orderId;
            $params[] = $product["product_id"];
            $params[] = $product["quantity"];
            $params[] = $product["price"];
            $sql = "INSERT INTO orders_have_products (order_id , product_id , quantity,price) 
                    VALUES (?,? ,? ,?)";
            $this->prepareAndExecute(
                $sql,
                $params
            );
        }
    }

    /**
     * @param $addressId int
     * @param $totalPrice float
     * @param $userId int
     *
     * @return int
     */
    function addOrder(
        $addressId,
        $totalPrice,
        $userId
    ) {
        $params = [];
        $params[] = $userId;
        $params[] = $addressId;
        $params[] = $totalPrice;
        $sql = "INSERT INTO orders (user_id , address_id , price)
                VALUES (?,?,?)";
        $this->prepareAndExecute(
            $sql,
            $params
        );

        return $this->lastInsertId();
    }

    /**
     * @param $userId int
     *
     * @return array
     */
    public function showOrders($userId)
    {
        $params = [];
        $params[] = $userId;
        $sql = "SELECT o.id, o.address_id , op.product_id , op.quantity, o.price,op.price as productPrice, p.name ,p.image_url , o.date_created  FROM orders as o
                JOIN orders_have_products as op
                ON o.id = op.order_id 
                JOIN products as p ON p.id = op.product_id
                WHERE o.user_id = ? 
                ORDER BY o.date_created DESC";

        return $this->fetchAllAssoc(
            $sql,
            $params
        );
    }

    /**
     * @param $orderedProducts array
     * @param $totalPrice float
     * @param $userId int
     */
    function finishOrder(
        $orderedProducts,
        $totalPrice,
        $userId
    ) {
        try {
            $this->beginTransaction();
            $id = $this->addOrder($_POST["address"], $totalPrice, $_SESSION["logged_user_id"]);
            $orderDao = new OrderDAO();
            $orderDao->addOrderProducts(
                $id,
                $orderedProducts
            );
            $quantity = new ProductDAO();
            $quantity->decreaseProductQuantity($orderedProducts);
            $cart = new CartDAO();
            $cart->deleteCart($userId);
            $this->commit();
        } catch (PDOException $e) {
            $this->rollBack();
            echo $e->getMessage();
        }
    }
}