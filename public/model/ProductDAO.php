<?php

namespace model;

use PDO;
use PDOException;

class ProductDAO extends AbstractDAO
{
    /**
     * @return array
     */
    public function getProducers()
    {
        $sql = '
            SELECT 
                * 
            FROM 
                producers
                ;';

        return $this->fetchAllObject($sql);
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
                types
                ;';

        return $this->fetchAllObject($sql);
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function getById($id)
    {
        $params = [];
        $params["productId"] = $id;
        $sql = '
            SELECT
                p.name,
                p.producer_id,
                pr.name AS producer_name,
                p.price,
                p.old_price,
                p.type_id,
                t.name AS type_name,
                p.quantity,
                p.image_url
            FROM 
                products AS p 
                JOIN producers AS pr ON(p.producer_id=pr.id)
                JOIN types AS t ON (p.type_id=t.id)
            WHERE 
                p.id = :productId;';

        return $this->fetchOneAssoc(
            $sql,
            $params
        );
    }

    /**
     * @param string $product_name
     * @param int $producer_id
     * @param float $product_price
     * @param int $type_id
     * @param int $quantity
     * @param string $image_url
     */
    public function add($product_name,
                        $producer_id,
                        $product_price,
                        $type_id,
                        $quantity,
                        $image_url
    ) {
        $params = [];
        $params["productName"] = $product_name;
        $params["producerId"] = $producer_id;
        $params["productPrice"] = $product_price;
        $params["typeId"] = $type_id;
        $params["quantity"] = $quantity;
        $params["imageUrl"] = $image_url;
        $sql = '
            INSERT INTO 
                products 
                (name,
                 producer_id,
                 price,
                 type_id,
                 quantity,
                 image_url,
                 date_created) 
            VALUES 
                (:productName,
                 :producerId,
                 :productPrice,
                 :typeId,
                 :quantity,
                 :imageUrl,
                 now())
                 ;';
        $this->prepareAndExecute(
            $sql,
            $params
        );
        $product_id = ($this->lastInsertId());
        new Product(
            $product_id,
            $product_name,
            $producer_id,
            $product_price,
            $type_id,
            $quantity,
            $image_url
        );
    }

    /**
     * @param array $product
     */
    public function edit(array $product)
    {
        $params = [];
        $params["name"] = $product["name"];
        $params["producerId"] = $product["producer_id"];
        $params["price"] = $product["price"];
        $params["oldPrice"] = $product["old_price"];
        $params["typeId"] = $product["type_id"];
        $params["quantity"] = $product["quantity"];
        $params["imageUrl"] = $product["image_url"];
        $params["productId"] = $product["product_id"];
        $sql = '
            UPDATE
                products
            SET 
                name = :name,
                producer_id = :producerId,
                price = :price,
                old_price = :oldPrice,
                type_id = :typeId,
                quantity = :quantity,
                image_url = :imageUrl 
            WHERE 
                id = :productId ;';
        $this->prepareAndExecute(
            $sql,
            $params
        );
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function getProductsFromTypeId($id)
    {
        $params = [];
        $params["typeId"] = $id;
        $sql = '
            SELECT
                id,
                name
            FROM 
                products 
            WHERE
                type_id = :typeId
                ';

        return $this->fetchAllAssoc(
            $sql,
            $params
        );
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function getProductsFromTypeIdAsc($id)
    {
        $params = [];
        $params["id"] = $id;
        $sql = '
            SELECT
                id,
                name
            FROM
                products 
            WHERE
                type_id = :id 
            ORDER BY
                price ASC';

        return $this->fetchAllAssoc(
            $sql,
            $params
        );
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function getProductsFromTypeIdDesc($id)
    {
        $params = [];
        $params["id"] = $id;
        $sql = '
            SELECT
                id,
                name 
            FROM 
                products 
            WHERE 
                type_id = :id
            ORDER BY
                price DESC
                ';

        return $this->fetchAllAssoc(
            $sql,
            $params
        );
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function checkQuantity($id)
    {
        $params = [];
        $params["id"] = $id;
        $sql = '
            SELECT
                quantity
            FROM 
                products 
            WHERE 
                id = :id
                ';

        return $this->fetchOneAssoc(
            $sql,
            $params
        );
    }

    /**
     * @param int $id
     *
     * @return Product $product
     */
    public function findProduct($id)
    {
        $params = [];
        $params["id"] = $id;
        $sql = '
            SELECT 
                id,
                name,
                producer_id,
                price,
                type_id,
                quantity,
                image_url 
            FROM
                products 
            WHERE
                id = :id
                ';
        $rows = $this->fetchOneAssoc(
            $sql,
            $params
        );
        $product = new Product(
            $rows["id"],
            $rows["name"],
            $rows["producer_id"],
            $rows["price"],
            $rows["type_id"],
            $rows["quantity"],
            $rows["image_url"]
        );

        return $product;
    }

    /**
     * @param array $orderedProducts
     */
    public function decreaseProductQuantity($orderedProducts)
    {
        foreach ($orderedProducts as $product)
        {
            $params = [];
            $params["quantity"] = $product["quantity"];
            $params["productId"] = $product["product_id"];
            $sql = '
                UPDATE
                    products
                SET 
                    quantity = quantity - :quantity 
                WHERE 
                    id = :productId
                    ';
            $this->prepareAndExecute(
                $sql,
                $params
            );
        }
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function getProductAttributes($id)
    {
        $params = [];
        $params["id"] = $id;
        $sql = '
            SELECT 
                name
            FROM 
                attributes 
            WHERE
                type_id = :id
                ';

        return $this->fetchAllAssoc(
            $sql,
            $params
        );
    }

    /**
     * @param int $typeId
     * @param string $attributeName
     *
     * @return array
     */
    public function getAttributeValues(
        $typeId,
        $attributeName
    ) {
        $params = [];
        $params ["typeId"] = $typeId;
        $params ["attributeName"] = $attributeName;
        $sql = '
             SELECT 
                value 
             FROM 
                product_attributes 
                JOIN attributes ON attribute_id = id 
             WHERE
                type_id = :typeId AND name = :attributeName
                ';

        return $this->fetchAllAssoc(
            $sql,
            $params
        );
    }

    /**
     * @param int $product_id
     * @param float $price
     */
    public function removePromotion(
        $product_id,
        $price
    ) {
        $params = [];
        $params["price"] = $price;
        $params["productId"] = $product_id;
        $sql = '
            UPDATE 
                products
            SET 
                price = :price, old_price = NULL 
            WHERE
                id = :productId 
                ;';
        $this->prepareAndExecute(
            $sql,
            $params
        );
    }

    /**
     * @param string $filters
     * @param array $params
     */
    public function filterProducts(
        $filters,
        $params
    ) {
        $sql = $filters;
        $products = $this->fetchAllAssoc(
            $sql,
            $params
        );

        echo json_encode($products);
        error_log(json_encode($products));
    }

    /**
     * @param int $productId
     *
     * @return array
     */
    public function getUserEmailsByLikedProduct($productId)
    {
        $params = [];
        $params["productId"] = $productId;
        $sql = '
            SELECT 
                email 
            FROM 
                users AS u
                JOIN user_favourite_products as uf ON u.id = uf.user_id
            WHERE 
                uf.product_id = :productId and u.subscription = "yes"
                ';

        return $this->fetchAllAssoc(
            $sql,
            $params
        );
    }

    /**
     * @return array
     */
    public function getMostSold()
    {
        $sql = '
            SELECT
                p.id,
                p.name,
                p.producer_id,
                p.price,
                p.old_price,
                p.image_url,
                count(ohp.product_id) as ordered_count 
            FROM 
                emag.products AS p
                JOIN orders_have_products AS ohp ON(p.id=ohp.product_id)
            GROUP BY
                p.id
            ORDER BY 
                ordered_count DESC LIMIT 6
                ;';

        return $this->fetchAllObject($sql);
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function getProductAttributesById($id)
    {
        $params = [];
        $params["id"] = $id;
        $sql = '
            SELECT 
                a.name,pa.value 
            FROM 
                attributes AS a
                JOIN product_attributes AS pa ON(a.id=pa.attribute_id)
            WHERE 
                product_id = :id
                ;';

        return $this->fetchAllObject(
            $sql,
            $params
        );
    }
}