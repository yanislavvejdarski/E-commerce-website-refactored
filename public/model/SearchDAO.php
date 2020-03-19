<?php

namespace model;

use PDO;
use PDOException;

class SearchDAO extends AbstractDAO
{
    /**
     * @param $keywords string
     *
     * @return array
     */
    public function searchProduct($keywords)
    {
        $params = [];
        $params[] = "{$keywords}%";
        $sql = "SELECT id , name FROM products 
                WHERE name LIKE ? LIMIT 5;";

        return $this->fetchAllAssoc(
            $sql,
            $params
        );
    }

    /**
     * @param $keywords string
     *
     * @return array
     */
    public function searchCategorie($keywords)
    {
        $params = [];
        $params[] = "{$keywords}%";
        $sql = "SELECT c.id, c.name  FROM categories AS c  
			    WHERE name LIKE ?;";

        return $this->fetchAllAssoc(
            $sql,
            $params
        );
    }

    /**
     * @param $keywords string
     *
     * @return array
     */
    public function searchType($keywords)
    {
        $params = [];
        $params[] = "{$keywords}%";
        $sql = "SELECT id, name FROM types 
                WHERE name LIKE ? LIMIT 4;";

        return $this->fetchAllAssoc(
            $sql,
            $params
        );
    }
}