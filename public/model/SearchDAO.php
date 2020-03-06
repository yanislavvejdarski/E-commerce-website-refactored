<?php
namespace model;
use PDO;
use PDOException;


class SearchDAO
{
   public function searchProduct ($keywords){

            $params = [];
            $params[] = "{$keywords}%";

       $pdo = DBManager::getInstance()->getPDO();

       $sql = "SELECT id , name FROM products WHERE name LIKE ? LIMIT 4;";
            $statement = $pdo->prepare($sql);
            $statement->execute($params);
            $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $rows;

    }

    public function searchCategorie ($keywords){

            $params = [];
            $params[] = "{$keywords}%";

        $pdo = DBManager::getInstance()->getPDO();


        $sql = "
                SELECT c.id, c.name  FROM categories AS c  
			    WHERE name LIKE ?;";
            $statement = $pdo->prepare($sql);
            $statement->execute($params);
            $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $rows;

    }

    public  function searchType ($keywords){

            $params = [];
            $params[] = "{$keywords}%";
            $pdo = DBManager::getInstance()->getPDO();
            $sql = "SELECT id, name FROM types WHERE name LIKE ? LIMIT 4;";
            $statement = $pdo->prepare($sql);
            $statement->execute($params);
            $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $rows;

    }
}
