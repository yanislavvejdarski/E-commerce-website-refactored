<?php

namespace model;

use PDO;
use PDOException;

class SearchDAO extends AbstractDAO
{
    /**
     * @param string $keywords
     *
     * @return array
     */
    public function searchProduct($keywords)
    {
        $params = [];
        $params["keyWords"] = "{$keywords}%";
        $sql = '
            SELECT 
                id,
                name 
            FROM
                products 
            WHERE
                name LIKE :keyWords LIMIT 5
                ;';

        return $this->fetchAllAssoc(
            $sql,
            $params
        );
    }

    /**
     * @param string $keywords
     *
     * @return array
     */
    public function searchCategorie($keywords)
    {
        $params = [];
        $params["keyWords"] = "{$keywords}%";
        $sql = '
            SELECT 
                c.id,
                c.name  
            FROM 
                categories AS c  
            WHERE 
                name LIKE :keyWords
                ;';

        return $this->fetchAllAssoc(
            $sql,
            $params
        );
    }

    /**
     * @param string $keywords
     *
     * @return array
     */
    public function searchType($keywords)
    {
        $params = [];
        $params["keyWords"] = "{$keywords}%";
        $sql = '
            SELECT
                id,
                name
            FROM
                types 
            WHERE
                name LIKE :keyWords LIMIT 4
                ;';

        return $this->fetchAllAssoc(
            $sql,
            $params
        );
    }
}