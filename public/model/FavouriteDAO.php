<?php
namespace model;
use PDO;
use PDOException;

class FavouriteDAO{
    public function showFavourites ($userId){

        $params = [];
        $params[] = $userId;
        $pdo = DBManager::getInstance()->getPDO();
        $sql = "SELECT product_id FROM user_favourite_products WHERE user_id = ? ";
        $statement = $pdo->prepare($sql);
        $statement->execute($params);
        $rows =$statement->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
    public function addToFavourites ($id , $userId){

        $params = [];
        $params[] = $userId;
        $params[] = $id;
        $pdo = DBManager::getInstance()->getPDO();
        $sql = "INSERT INTO user_favourite_products (user_id , product_id ) VALUES (? ,?)";
        $statement = $pdo->prepare($sql);
        $statement->execute($params);

    }
    public function deleteFromFavourites ($id , $userId){
        $params = [];
        $params[] = $userId;
        $params[] = $id;
        $pdo = DBManager::getInstance()->getPDO();
        $sql = "DELETE FROM user_favourite_products WHERE user_id = ? AND product_id = ? ";
        $statement = $pdo->prepare($sql);
        $statement->execute($params);
    }

    public function checkIfInFavourites($id , $userId){
        $params = [];
        $params[] = $id;
        $params[] = $userId;
        $pdo = DBManager::getInstance()->getPDO();
        $sql = "SELECT product_id FROM user_favourite_products WHERE product_id = ? AND user_id = ?";
        $statement = $pdo->prepare($sql);
        $statement->execute($params);
        $rows = $statement->fetch(PDO::FETCH_ASSOC);
        return $rows;

    }

}