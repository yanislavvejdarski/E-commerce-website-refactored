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
        $sql = "SELECT * FROM producers;";

        return $this->fetchAllObject($sql);
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        $sql = "SELECT * FROM types;";

        return $this->fetchAllObject($sql);
    }

    /**
     * @param $id int
     *
     * @return mixed
     */
    public function getById($id)
    {
        $params = [];
        $params[] = $id;
        $sql = "SELECT p.name, p.producer_id, pr.name AS producer_name,
                    p.price,p.old_price, p.type_id, t.name AS type_name,p.quantity,p.image_url
                    FROM products AS p 
                    JOIN producers AS pr ON(p.producer_id=pr.id)
                    JOIN types AS t ON (p.type_id=t.id)
                    WHERE p.id=?;";

        return $this->fetchOneAssoc(
            $sql,
            $params
        );
    }

    /**
     * @param $product_name string
     * @param $producer_id int
     * @param $product_price float
     * @param $type_id int
     * @param $quantity int
     * @param $image_url string
     */
    public function add($product_name,
                        $producer_id,
                        $product_price,
                        $type_id,
                        $quantity,
                        $image_url
    ) {
        $params = [];
        $params[] = $product_name;
        $params[] = $producer_id;
        $params[] = $product_price;
        $params[] = $type_id;
        $params[] = $quantity;
        $params[] = $image_url;
        $sql = "INSERT INTO products (name, producer_id, price,type_id,quantity,image_url,date_created) 
                VALUES (?,?,?,?,?,?,now());";
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
     * @param $product array
     */
    public function edit(array $product)
    {
        $params = [];
        $params[] = $product["name"];
        $params[] = $product["producer_id"];
        $params[] = $product["price"];
        $params[] = $product["old_price"];
        $params[] = $product["type_id"];
        $params[] = $product["quantity"];
        $params[] = $product["image_url"];
        $params[] = $product["product_id"];
        $sql = "UPDATE products SET name=?, producer_id=?,price=?,old_price=?, type_id=?, quantity=?, image_url=? 
                WHERE id=? ;";
        $this->prepareAndExecute(
            $sql,
            $params
        );
    }

    /**
     * @param $id int
     *
     * @return array
     */
    public function getProductsFromTypeId($id)
    {
        $params = [];
        $params[] = $id;
        $sql = "SELECT id , name FROM products 
                WHERE type_id = ?";

        return $this->fetchAllAssoc(
            $sql,
            $params
        );
    }

    /**
     * @param $id int
     *
     * @return array
     */
    public function getProductsFromTypeIdAsc($id)
    {
        $params = [];
        $params[] = $id;
        $sql = "SELECT id , name FROM products 
                WHERE type_id = ? 
                ORDER BY price ASC";

        return $this->fetchAllAssoc(
            $sql,
            $params
        );
    }

    /**
     * @param $id int
     *
     * @return array
     */
    public function getProductsFromTypeIdDesc($id)
    {
        $params = [];
        $params[] = $id;
        $sql = "SELECT id , name FROM products 
                WHERE type_id = ? 
                ORDER BY price DESC";

        return $this->fetchAllAssoc(
            $sql,
            $params
        );
    }

    /**
     * @param $id int
     *
     * @return array
     */
    public function checkQuantity($id)
    {
        $params = [];
        $params[] = $id;
        $sql = "SELECT quantity FROM products 
                WHERE id = ?";

        return $this->fetchOneAssoc(
            $sql,
            $params
        );
    }

    /**
     * @param $id int
     *
     * @return object
     */
    public function findProduct($id)
    {
        $params = [];
        $params[] = $id;
        $sql = "SELECT id , name , producer_id , price , type_id , quantity , image_url 
                FROM products 
                WHERE id = ?";
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
     * @param $orderedProducts array
     */
    public function decreaseProductQuantity($orderedProducts)
    {
        foreach ($orderedProducts as $product) {
            $params = [];
            $params[] = $product["quantity"];
            $params[] = $product["product_id"];
            $sql = "UPDATE products SET quantity = quantity - ? 
                    WHERE id = ?";
            $this->prepareAndExecute(
                $sql,
                $params
            );
        }
    }

    /**
     * @param $id int
     *
     * @return array
     */
    public function getProductAttributes($id)
    {
        $params = [];
        $params[] = $id;
        $sql = "SELECT name  FROM attributes 
                WHERE type_id = ?";

        return $this->fetchAllAssoc(
            $sql,
            $params
        );
    }

    /**
     * @param $typeId int
     * @param $attributeName string
     *
     * @return array
     */
    public function getAttributeValues(
        $typeId,
        $attributeName
    ) {
        $params = [];
        $params [] = $typeId;
        $params [] = $attributeName;
        $sql = " SELECT value FROM product_attributes 
                 JOIN attributes ON attribute_id = id 
                 WHERE type_id = ? AND name = ?";

        return $this->fetchAllAssoc(
            $sql,
            $params
        );
    }

    /**
     * @param $product_id int
     * @param $price float
     */
    public function removePromotion(
        $product_id,
        $price
    ) {
        $params = [];
        $params[] = $price;
        $params[] = $product_id;
        $sql = "UPDATE products SET price=?, old_price=NULL 
                WHERE id=? ;";
        $this->prepareAndExecute(
            $sql,
            $params
        );
    }

    /**
     * @param $filters string
     * @param $params array
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
     * @param $productId int
     *
     * @return array
     */
    public function getUserEmailsByLikedProduct($productId)
    {
        $params = [];
        $params[] = $productId;
        $sql = "SELECT email FROM users as u JOIN user_favourite_products as uf ON u.id = uf.user_id
                WHERE uf.product_id = ? and u.subscription = 'yes'";

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
        $sql = "SELECT p.id,p.name,p.producer_id,p.price,p.old_price,p.image_url,count(ohp.product_id) as 
                ordered_count FROM emag.products AS p
                JOIN orders_have_products AS ohp ON(p.id=ohp.product_id)
                GROUP BY p.id ORDER BY ordered_count DESC LIMIT 6;";

        return $this->fetchAllObject($sql);
    }

    /**
     * @param $id int
     *
     * @return array
     */
    public function getProductAttributesById($id)
    {
        $params = [];
        $params[] = $id;
        $sql = "SELECT a.name,pa.value FROM attributes AS a
                JOIN product_attributes AS pa ON(a.id=pa.attribute_id)
                WHERE product_id =?;";

        return $this->fetchAllObject(
            $sql,
            $params
        );
    }
}