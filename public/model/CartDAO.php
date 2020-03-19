<?php
namespace model;

use PDO;
use PDOException;

class CartDAO extends AbstractDAO {

    /**
     * @param $id int
     * @param $userId int
     *
     * @return array
     */
  public  function checkIfInCart(
      $id,
      $userId
  ) {
            $params = [];
            $params[] = $id;
            $params[] = $userId;
            $sql = "SELECT user_id , product_id , quantity 
                    FROM cart 
                    WHERE product_id = ? AND user_id = ?";

          return  $this->fetchOneAssoc(
              $sql,
              $params);
    }

    /**
     * @param $productId int
     * @param $userId int
     */
    public  function putInCart(
        $productId,
        $userId
    ) {
            $params = [];
            $params[] = $userId;
            $params[] = $productId;
            $params[] = 1;
            $sql = "INSERT INTO cart (user_id , product_id , quantity) 
                    VALUES (?,?,?)";
            $this->prepareAndExecute(
                $sql,
                $params
            );
    }

    /**
     * @param $id int
     * @param $userId int
     */
    public function updateQuantityOfProductInCart(
        $id,
        $userId) {
            $params = [];
            $params[] = $userId;
            $params[] = $id;
            $sql = "UPDATE cart SET quantity = quantity + 1 
                    WHERE user_id = ? AND product_id = ?";
            $this->prepareAndExecute(
                $sql,
                $params
            );
    }

    /**
     * @param $id int
     */
    public function showCart ($id) {
            $params = [];
            $params[] = $id;
            $sql = "SELECT c.product_id , c.quantity , price*c.quantity as price 
                    FROM cart as c 
                    JOIN products as p on c.product_id = p.id  
                    WHERE user_id = ?";
            $this->fetchAllAssoc(
                $sql,
                $params
            );
    }

    /**
     * @param $id int
     * @param $quantity int
     * @param $userId int
     */
    public  function updateCartQuantity(
        $id,
        $quantity,
        $userId
    ) {
            $params = [];
            $params[] = $quantity;
            $params[] = $userId;
            $params[] = $id;
            $sql = "UPDATE cart SET quantity = ? 
                    WHERE user_id = ? AND product_id = ?";
            $this->prepareAndExecute(
                $sql,
                $params
            );
    }

    /**
     * @param $id int
     * @param $userId int
     */
    public  function deleteProductFromCart(
        $id,
        $userId
    ) {
            $params = [];
            $params[] = $userId;
            $params[] = $id;
            $sql = "DELETE FROM cart 
                    WHERE user_id = ? AND product_id = ? ";
            $this->prepareAndExecute(
                $sql,
                $params
            );
    }

    /**
     * @param $userId int
     */
    public  function deleteCart ($userId) {
            $params = [];
            $params[] = $userId;
            $sql = "DELETE FROM cart 
                    WHERE user_id = ? ";
            $this->prepareAndExecute(
                $sql,
                $params
            );
    }
}