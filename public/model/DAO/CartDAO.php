<?php
namespace model\DAO;

use PDO;
use PDOException;

class CartDAO extends AbstractDAO
{
    /**
     * @param int $id
     * @param int $userId
     *
     * @return array
     */
  public  function checkIfInCart(
      $id,
      $userId
  ) {
            $params = [
                'productId' => $id,
                'userId' => $userId
            ];
            $sql = '
                SELECT 
                    user_id,
                    product_id,
                    quantity 
                FROM 
                    cart 
                WHERE 
                    product_id = :productId AND user_id = :userId
            ';

          return  $this->fetchOneAssoc(
              $sql,
              $params);
    }

    /**
     * @param int $productId
     * @param int $userId
     */
    public  function putInCart(
        $productId,
        $userId
    ) {
            $params = [
                'userId' => $userId,
                'productId' => $productId,
                'quantity' => 1
            ];
            $sql = '
                INSERT INTO
                    cart 
                    (user_id,
                     product_id,
                     quantity) 
                VALUES 
                    (:userId,
                     :productId,
                     :quantity)
            ';
            $this->prepareAndExecute(
                $sql,
                $params
            );
    }

    /**
     * @param int $id
     * @param int $userId
     */
    public function updateQuantityOfProductInCart(
        $id,
        $userId) {
            $params = [
                'userId' => $userId,
                'productId' => $id
            ];
            $sql = '
                UPDATE 
                    cart 
                SET 
                    quantity = quantity + 1 
                WHERE 
                    user_id = :userId AND product_id = :productId
            ';
            $this->prepareAndExecute(
                $sql,
                $params
            );
    }

    /**
     * @param int $id
     */
    public function showCart ($id)
    {
            $params = ['userId' => $id];
            $sql = '
                SELECT 
                    c.product_id,
                    c.quantity,
                    price*c.quantity AS price 
                FROM 
                    cart AS c 
                    JOIN products AS p ON c.product_id = p.id  
                WHERE 
                    user_id = :userId
            ';
           return $this->fetchAllAssoc(
                $sql,
                $params
            );
    }

    /**
     * @param int $id
     * @param int $quantity
     * @param int $userId
     */
    public  function updateCartQuantity(
        $id,
        $quantity,
        $userId
    ) {
            $params = [
                'quantity' => $quantity,
                'userId' => $userId,
                'productId' => $id
            ];
            $sql = '
                UPDATE 
                    cart
                SET 
                    quantity = :quantity 
                WHERE
                    user_id = :userId AND product_id = :productId
            ';
            $this->prepareAndExecute(
                $sql,
                $params
            );
    }

    /**
     * @param int $id
     * @param int $userId
     */
    public  function deleteProductFromCart(
        $id,
        $userId
    ) {
            $params = [
                'userId' => $userId,
                'productId' => $id
            ];
            $sql = '
                DELETE FROM
                        cart 
                WHERE
                    user_id = :userId AND product_id = :productId 
            ';
            $this->prepareAndExecute(
                $sql,
                $params
            );
    }

    /**
     * @param int $userId
     */
    public  function deleteCart ($userId)
    {
            $params = ['userId' => $userId];
            $sql = '
                DELETE FROM
                        cart 
                WHERE 
                    user_id = :userId 
            ';
            $this->prepareAndExecute(
                $sql,
                $params
            );
    }
}