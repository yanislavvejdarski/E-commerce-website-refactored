<?php
namespace model;
use PDO;
use PDOException;



class ProductDAO{

    public function getProducers(){
        $pdo = DBManager::getInstance()->getPDO();
        $sql="SELECT * FROM producers;";
        $stmt=$pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);

    }

    public function getTypes(){

            $pdo = DBManager::getInstance()->getPDO();
            $sql="SELECT * FROM types;";
            $stmt=$pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_OBJ);

    }

    public  function getById($id){
        $pdo = DBManager::getInstance()->getPDO();
        $sql=   "SELECT p.name, p.producer_id, pr.name AS producer_name,
                    p.price,p.old_price, p.type_id, t.name AS type_name,p.quantity,p.image_url
                    FROM products AS p 
                    JOIN producers AS pr ON(p.producer_id=pr.id)
                    JOIN types AS t ON (p.type_id=t.id)
                    WHERE p.id=?;";
        $stmt=$pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);

    }

    public  function add($product_name,$producer_id,$product_price,$type_id,$quantity,$image_url) {

        $pdo = DBManager::getInstance()->getPDO();
        $params = [];
        $params[] = $product_name;
        $params[] = $producer_id;
        $params[] = $product_price;
        $params[] = $type_id;
        $params[] = $quantity;
        $params[] = $image_url;
        $sql = "INSERT INTO products (name, producer_id, price,type_id,quantity,image_url,date_created) VALUES (?,?,?,?,?,?,now());";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $product_id=($pdo->lastInsertId());
        new Product($product_id,$product_name,$producer_id,$product_price,$type_id,$quantity,$image_url);

    }

    public function edit(array $product)
    {
        $pdo = DBManager::getInstance()->getPDO();

        $params = [];
        $params[] = $product["name"];
        $params[] = $product["producer_id"];
        $params[] = $product["price"];
        $params[]=$product["old_price"];
        $params[] = $product["type_id"];
        $params[] = $product["quantity"];
        $params[] = $product["image_url"];
        $params[] = $product["product_id"];

        $sql = "UPDATE products SET name=?, producer_id=?,price=?,old_price=?, type_id=?, quantity=?, image_url=? WHERE id=? ;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

    }

    public function getProductsFromTypeId($id){
            $params = [];
            $params[] = $id;
            $pdo = DBManager::getInstance()->getPDO();
            $sql = "SELECT id , name FROM products WHERE type_id = ?";
            $statement = $pdo->prepare($sql);
            $statement->execute($params);
            $products = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $products;

    }
    public function getProductsFromTypeIdAsc($id){

            $params = [];
            $params[] = $id;
            $pdo = DBManager::getInstance()->getPDO();
            $sql = "SELECT id , name FROM products WHERE type_id = ? ORDER BY price ASC";
            $statement = $pdo->prepare($sql);
            $statement->execute($params);
            $products = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $products;
        }

    public function getProductsFromTypeIdDesc($id){

            $params = [];
            $params[] = $id;
            $pdo = DBManager::getInstance()->getPDO();
            $sql = "SELECT id , name FROM products WHERE type_id = ? ORDER BY price DESC";
            $statement = $pdo->prepare($sql);
            $statement->execute($params);
            $products = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $products;

    }
    public function checkQuantity ($id){

        $params = [];
        $params[] = $id;
        $pdo = DBManager::getInstance()->getPDO();
        $sql = "SELECT quantity FROM products WHERE id = ?";
        $statement = $pdo->prepare($sql);
        $statement->execute($params);
        $quantity = $statement->fetch(PDO::FETCH_ASSOC);
        return $quantity;
    }

    public function findProduct ($id){
        $pdo = DBManager::getInstance()->getPDO();
       $sql = "SELECT id , name , producer_id , price , type_id , quantity , image_url FROM products WHERE id = ?";
       $statement = $pdo->prepare($sql);
       $statement->execute([$id]);
       $rows = $statement->fetch(PDO::FETCH_ASSOC);
       $product = new Product($rows["id"] , $rows["name"] , $rows["producer_id"] , $rows["price"] , $rows["type_id"]
           , $rows["quantity"] , $rows["image_url"]);

       return $product;
    }
    public function decreaseProductQuantity($orderedProducts){
        foreach ($orderedProducts as $product) {
            $params = [];
            $params[] = $product["quantity"];
            $params[] = $product["product_id"];
            $pdo = DBManager::getInstance()->getPDO();
            $sql = "UPDATE products SET quantity = quantity - ? WHERE id = ?";
            $statement = $pdo->prepare($sql);
            $statement->execute($params);
        }

    }
    public function getProductAttributes ($id){

        $params = [];
        $params[] = $id;
        $pdo = DBManager::getInstance()->getPDO();
        $sql = "SELECT name  FROM attributes WHERE type_id = ?";
        $statement = $pdo->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getAttributeValues($typeId , $attributeName){
        $params = [];
        $params [] = $typeId;
        $params [] = $attributeName;
        $pdo = DBManager::getInstance()->getPDO();
        $sql =" SELECT value FROM product_attributes JOIN attributes ON attribute_id = id WHERE type_id = ? AND name = ?";
        $statement = $pdo->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }


    public function removePromotion($product_id,$price)
    {

        $pdo = DBManager::getInstance()->getPDO();


        $params=[];
        $params[]=$price;
        $params[]=$product_id;
        $sql = "UPDATE products SET price=?, old_price=NULL WHERE id=? ;";
        $stmt=$pdo->prepare($sql);
        $stmt->execute($params);

    }

    public static function isInArray($array, $value){
        foreach($array as $a){
            if($a == $value){return true;}
        }
        return false;
    }

    public  static function filterProducts ($filters,$args){
        // echo $filters . "\n\n";
        $pdo = DBManager::getInstance()->getPDO();
        $sql = $filters;
        $statement = $pdo->prepare($sql);
        $statement->execute($args);
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($products);
        error_log(json_encode($products));
    }
   public static function getProductsEmptyFilter($withoutFilter){
       $pdo = DBManager::getInstance()->getPDO();
       $sql = $withoutFilter;
        $statement = $pdo->prepare($sql);
        $statement->execute();
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($products);
        }

    public function getUserEmailsByLikedProduct($productId){

        $pdo = DBManager::getInstance()->getPDO();

        $params = [];
        $params[] = $productId;
        $sql = "SELECT email FROM users as u JOIN user_favourite_products as uf ON u.id = uf.user_id
         WHERE uf.product_id = ? and u.subscription = 'yes'";
        $statement = $pdo->prepare($sql);
        $statement->execute($params);
        $emails = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $emails;

    }

    public function getMostSold(){

        $pdo = DBManager::getInstance()->getPDO();


        $sql = "SELECT p.id,p.name,p.producer_id,p.price,p.old_price,p.image_url,count(ohp.product_id) as 
ordered_count FROM emag.products AS p
JOIN orders_have_products AS ohp ON(p.id=ohp.product_id)
group by p.id order by ordered_count desc LIMIT 6;";
        $statement = $pdo->prepare($sql);
        $statement->execute();
        $emails = $statement->fetchAll(PDO::FETCH_OBJ);
        return $emails;

    }
    public function getProductAttributesById ($id){

        $pdo = DBManager::getInstance()->getPDO();
        $sql = "SELECT a.name,pa.value FROM attributes AS a
JOIN product_attributes AS pa ON(a.id=pa.attribute_id)
WHERE product_id =?;";
        $statement = $pdo->prepare($sql);
        $statement->execute([$id]);
        return $statement->fetchAll(PDO::FETCH_OBJ);

    }


}
