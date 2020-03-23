<?php

namespace model\DAO;

//use PDOException;

class OrderDAO extends AbstractDAO
{
    /**
     * @param int $orderId
     * @param array $orderedProducts
     */
    function addOrderProducts(
        $orderId,
        $orderedProducts
    ) {
        foreach ($orderedProducts as $product)
        {
            $params = [
                'orderId' => $orderId,
                'productId' => $product['product_id'],
                'quantity' => $product['quantity'],
                'price' => $product['price']
            ];
            $sql = '
                INSERT INTO 
                    orders_have_products 
                    (
                         order_id,
                         product_id,
                         quantity,
                         price
                     ) 
                VALUES 
                    (
                        :orderId,
                        :productId,
                        :quantity,
                        :price
                    )
            ';
            $this->prepareAndExecute(
                $sql,
                $params
            );
        }
    }

    /**
     * @param int $addressId
     * @param float $totalPrice
     * @param int $userId
     *
     * @return int
     */
    function addOrder(
        $addressId,
        $totalPrice,
        $userId
    ) {
        $params = [
            'userId' => $userId,
            'addressId' => $addressId,
            'totalPrice' => $totalPrice
        ];
        $sql = '
            INSERT INTO 
                orders 
                 (
                     user_id,
                     address_id,
                     price
                 )
            VALUES 
                (
                     :userId,
                     :addressId,
                     :totalPrice
                 )
        ';
        $this->prepareAndExecute(
            $sql,
            $params
        );

        return $this->lastInsertId();
    }

    /**
     * @param int $userId
     *
     * @return array
     */
    public function showOrders($userId)
    {
        $params = ['userId' => $userId];
        $sql = '
            SELECT 
                o.id,
                o.address_id,
                op.product_id,
                op.quantity,
                o.price,
                op.price as productPrice,
                p.name,
                p.image_url,
                o.date_created  
            FROM 
                orders as o
                JOIN orders_have_products as op ON o.id = op.order_id 
                JOIN products as p ON p.id = op.product_id
            WHERE
                o.user_id = :userId 
            ORDER BY
                o.date_created DESC
        ';

        return $this->fetchAllAssoc(
            $sql,
            $params
        );
    }

    /**
     * @param array $orderedProducts
     * @param float $totalPrice
     * @param int $userId
     */
    function finishOrder(
        $orderedProducts,
        $totalPrice,
        $userId
    ) {
        try {
            $this->beginTransaction();
            $id = $this->addOrder($_POST['address'], $totalPrice, $_SESSION['logged_user_id']);
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
        }
        catch (\PDOException $e)
        {
            $this->rollBack();
            echo $e->getMessage();
        }
    }
}
