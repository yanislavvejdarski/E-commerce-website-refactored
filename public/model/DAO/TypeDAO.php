<?php

namespace model\DAO;
use model\Type;
use PDO;

class TypeDAO extends AbstractDAO
{
    /**
     * @param int $id
     *
     * @return Type $type
     */
    public function getTypeInformation($id)
    {
        $params = ['typeId' => $id];
        $sql = '
            SELECT
                id,
                name,
                categorie_id
            FROM 
                types 
            WHERE
                id = :typeId
        ';
        $rows = $this->fetchOneAssoc(
            $sql,
            $params
        );
        $type = new Type(
            $rows['id'],
            $rows['name'],
            $rows['categorie_id']
        );

        return $type;
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function getTypesFromCategorieId($id)
    {
        $params = ['categoryId' => $id];
        $sql = '
            SELECT 
                id,
                name,
                categorie_id 
            FROM 
                types 
            WHERE
                categorie_id = :categoryId
        ';

        return $this->fetchAllAssoc(
            $sql,
            $params
        );
    }

    /**
     * @param int $id
     * @param int $start
     * @param int $productsPerPage
     *
     * @return array
     */
    public function getAllByType(
        $id
    ) {
        $params = ['typeId' => $id];
        $sql = '
            SELECT
                * 
            FROM 
                products 
            WHERE 
                type_id = :typeId 
        ';

        return $this->fetchAllObject($sql,$params);
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function getAttributesByType($id)
    {
        $params = ['id' => $id];
        $sql = '
            SELECT DISTINCT 
                a.name,
                pha.value 
            FROM 
                attributes as a 
                JOIN product_attributes as pha ON (a.id = pha.attribute_id)
            WHERE
                type_id = :id
        ';

        return $this->fetchAllObject(
            $sql,
            $params
        );
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function getNumberOfProductsForType($id)
    {
        $params = ['id' => $id];
        $sql = '
            SELECT 
                count(id) AS count
            FROM 
                products 
            WHERE 
                type_id = :id
        ';

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
        $sql = '
            SELECT 
                * 
            FROM 
                emag.types
        ';

        return $this->fetchAllObject($sql);
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        $sql = '
            SELECT 
                * 
            FROM 
                emag.categories
        ';

        return $this->fetchAllObject($sql);
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function existsType($id)
    {
        $params = ['id' => $id];
        $sql = '
            SELECT
                COUNT(id) as count
            FROM 
                types 
            WHERE 
                id = :id
        ';

        return $this->fetchOneAssoc(
            $sql,
            $params
        );
    }
}