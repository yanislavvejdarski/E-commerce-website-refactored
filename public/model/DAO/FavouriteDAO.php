<?php
namespace model\DAO;
use PDO;
use PDOException;

class FavouriteDAO extends AbstractDAO
{

    /**
     * @param int $userId
     *
     * @return array
     */
    public function showFavourites ($userId)
    {
        $params = ['userId' => $userId];
        $sql = '
            SELECT 
                product_id AS productId
            FROM 
                user_favourite_products 
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
     * @param int $userId
     */
    public function addToFavourites (
        $id,
        $userId
    ) {
        $params = [
            'userId' => $userId,
            'productId' => $id
        ];
        $sql = '
            INSERT INTO
                user_favourite_products 
                (
                     user_id,
                     product_id
                 ) 
            VALUES 
                (
                     :userId,
                     :productId
                 )
        ';
        $this->prepareAndExecute(
            $sql
            ,$params
        );
    }

    /**
     * @param int $id
     * @param int $userId
     */
    public function deleteFromFavourites (
        $id,
        $userId
    ) {
        $params = [
            'userId' => $userId,
            'productId' => $id
        ];
        $sql = '
            DELETE FROM
                user_favourite_products 
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
     *
     * @return array
     */
    public function checkIfInFavourites(
        $id,
        $userId
    ) {
        $params = [
            'productId' => $id,
            'userId' => $userId
        ];
        $sql = '
            SELECT 
                product_id AS productId
            FROM 
                user_favourite_products 
            WHERE 
                product_id = :productId AND user_id = :userId
        ';

        return $this->fetchOneAssoc(
            $sql,
            $params
        );
    }
}