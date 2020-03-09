<?php

namespace model;
use PDO;

class AddressDAO{




    public function getById($id){

        $pdo = DBManager::getInstance()->getPDO();
        $sql="SELECT a.id, a.city_id,a.user_id, c.name AS city_name,a.street_name 

                    FROM addresses AS a JOIN cities AS c ON(a.city_id=c.id)WHERE a.id=?;";
        $stmt=$pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    public function getCities(){
        $pdo = DBManager::getInstance()->getPDO();
            $sql="SELECT * FROM cities;";
            $stmt=$pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function add(Address $address) {

        $pdo = DBManager::getInstance()->getPDO();
            $params = [];
            $params[] = $address->getUserId();
            $params[] = $address->getCityId();
            $params[] = $address->getStreetName();
            $sql = "INSERT INTO addresses (user_id, city_id, street_name,date_created) VALUES (?, ?, ?,now());";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $address->setId($pdo->lastInsertId());


    }


    public function getAll($user_id) {
        $pdo = DBManager::getInstance()->getPDO();
            $sql = "SELECT a.id, c.name AS city_name,a.street_name 
                    FROM addresses AS a JOIN cities AS c ON(a.city_id=c.id)WHERE user_id=?;";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function edit(Address $address){

        $pdo = DBManager::getInstance()->getPDO();

            $params = [];
            $params[] = $address->getCityId();
            $params[] = $address->getStreetName();
            $params[] = $address->getId();
            $sql = "UPDATE addresses SET city_id=?, street_name=? WHERE id=? ;";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

    }

    public function delete($id){
        $pdo = DBManager::getInstance()->getPDO();
            $sql = "DELETE FROM addresses WHERE id=? ;";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);

    }


      public function userAddress($userId){
          $pdo = DBManager::getInstance()->getPDO();
        $sql = "SELECT id FROM addresses WHERE user_id = ?";
        $statement = $pdo->prepare($sql);
        $statement->execute([$userId]);
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;

      }

}