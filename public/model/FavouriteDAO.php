<?php
namespace model;
use PDO;
use PDOException;

class FavouriteDAO extends AbstractDAO {

    /**
     * @param $userId int
     *
     * @return array
     */
    public function showFavourites ($userId){
        $params = [];
        $params[] = $userId;
        $sql = "SELECT product_id FROM user_favourite_products WHERE user_id = ? ";

        return $this->fetchAllAssoc(
            $sql,
            $params
        );
    }

    /**
     * @param $id int
     * @param $userId int
     */
    public function addToFavourites (
        $id,
        $userId
    ) {
        $params = [];
        $params[] = $userId;
        $params[] = $id;
        $sql = "INSERT INTO user_favourite_products (user_id , product_id ) VALUES (? ,?)";
        $this->prepareAndExecute(
            $sql
            ,$params
        );
    }

    /**
     * @param $id int
     * @param $userId int
     */
    public function deleteFromFavourites (
        $id,
        $userId
    ) {
        $params = [];
        $params[] = $userId;
        $params[] = $id;
        $sql = "DELETE FROM user_favourite_products WHERE user_id = ? AND product_id = ? ";
        $this->prepareAndExecute(
            $sql,
            $params
        );
    }

    /**
     * @param $id int
     * @param $userId int
     *
     * @return array
     */
    public function checkIfInFavourites(
        $id,
        $userId
    ) {
        $params = [];
        $params[] = $id;
        $params[] = $userId;
        $sql = "SELECT product_id FROM user_favourite_products WHERE product_id = ? AND user_id = ?";

        return $this->fetchOneAssoc(
            $sql,
            $params
        );
    }
}