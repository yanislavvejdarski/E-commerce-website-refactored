<?php
namespace model;
use model\CartDAO;
use model\ProductDAO;
use PDO;
use PDOException;
class OrderDAO
{

    static function addOrderProducts($orderId, $orderedProducts)
    {
            foreach ($orderedProducts as $product) {
                $params = [];
                $params[] = $orderId;
                $params[] = $product["product_id"];
                $params[] = $product["quantity"];
                $params[] = $product["price"];
                $pdo = DBManager::getInstance()->getPDO();
                $sql = "INSERT INTO orders_have_products (order_id , product_id , quantity,price) VALUES (?,? ,? ,?)";
                $statement = $pdo->prepare($sql);
                $statement->execute($params);
            }
    }

    static function addOrder($addressId, $totalPrice , $userId)
    {
            $params = [];
            $params[] = $userId;
            $params[] = $addressId;
            $params[] = $totalPrice;
            $pdo = DBManager::getInstance()->getPDO();
            $sql = "INSERT INTO orders (user_id , address_id , price) VALUES (?,?,?)";
            $statement = $pdo->prepare($sql);
            $statement->execute($params);
            $id = $pdo->lastInsertId();
            return $id;
    }

    public function showOrders($userId)
    {
            $params = [];
            $params[] = $userId;
            $pdo = DBManager::getInstance()->getPDO();
            $sql = "SELECT o.id, o.address_id , op.product_id , op.quantity, o.price,op.price as productPrice, p.name ,p.image_url , o.date_created  FROM orders as o
            JOIN orders_have_products as op
            ON o.id = op.order_id 
            JOIN products as p ON p.id = op.product_id
            WHERE o.user_id = ? ORDER BY o.date_created DESC";
            $statement = $pdo->prepare($sql);
            $statement->execute($params);
            $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $rows;

    }

    static  function finishOrder($orderedProducts , $totalPrice , $userId){
        try{
            $pdo = DBManager::getInstance()->getPDO();
            $pdo->beginTransaction();
            $id = OrderDAO::addOrder($_POST["address"], $totalPrice , $_SESSION["logged_user_id"]);
            OrderDAO::addOrderProducts($id , $orderedProducts);
            $quantity = new ProductDAO();
            $quantity->decreaseProductQuantity($orderedProducts);
            $cart = new CartDAO();
            $cart->deleteCart($userId);
            $pdo->commit();
        }
        catch (PDOException $e){
            $pdo->rollBack();
            echo $e->getMessage();

        }

    }
}

