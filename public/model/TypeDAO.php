<?php

namespace model;

use PDO;

class TypeDAO extends AbstractDAO
{
    /**
     * @param $id int
     *
     * @return object
     */
    public function getTypeInformation($id)
    {
        $params = [];
        $params[] = $id;
        $sql = "SELECT id , name , categorie_id FROM types WHERE id = ? ";
        $rows = $this->fetchOneAssoc(
            $sql,
            $params
        );
        $type = new Type($rows["id"], $rows["name"], $rows["categorie_id"]);

        return $type;
    }

    /**
     * @param $id int
     *
     * @return array
     */
    public function getTypesFromCategorieId($id)
    {
        $params = [];
        $params[] = $id;
        $sql = "SELECT id , name , categorie_id FROM types WHERE categorie_id = ?";

        return $this->fetchAllAssoc(
            $sql,
            $params
        );
    }

    /**
     * @param $id int
     * @param $start int
     * @param $productsPerPage int
     *
     * @return array
     */
    public function getAllByType(
        $id,
        $start,
        $productsPerPage
    ) {
        $params = [];
        $params[] = $id;
        $sql = "SELECT * FROM products where type_id=? LIMIT " . $start . "," . $productsPerPage . ";";

        return $this->fetchAllObject($sql,$params);
    }

    /**
     * @param $id int
     *
     * @return array
     */
    public function getAttributesByType($id)
    {
        $params = [];
        $params[] = $id;
        $sql = "SELECT distinct a.name, pha.value FROM attributes as a 
                JOIN product_attributes as pha ON(a.id=pha.attribute_id)
                WHERE type_id=?;";

        return $this->fetchAllObject(
            $sql,
            $params
        );
    }

    /**
     * @param $id int
     *
     * @return array
     */
    public function getNumberOfProductsForType($id)
    {
        $params = [];
        $params[] = $id;
        $sql = "SELECT count(id) AS count FROM products where type_id=?;";

        return $this->fetchOneObject(
            $sql,
            $params
        );
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        $sql = "SELECT * FROM emag.types;";

        return $this->fetchAllObject($sql);
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        $sql = "SELECT * FROM emag.categories;";

        return $this->fetchAllObject($sql);
    }

    /**
     * @param $id int
     *
     * @return mixed
     */
    public function existsType($id)
    {
        $params = [];
        $params[] = $id;
        $sql = "SELECT COUNT(id) as count FROM types WHERE id = ?";

        return $this->fetchOneAssoc(
            $sql,
            $params
        );
    }
}