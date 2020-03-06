<?php
namespace controller;

use exception\BadRequestException;
use model\Filter;
use model\ProductDAO;
use model\Type;
use model\TypeDAO;
use PHPMailer;


include_once "credentials.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ProductController
{
    public function show()
    {
        if (isset($_GET["prdId"])) {

                $productDAO = new ProductDAO();
                $product = $productDAO->findProduct($_GET["prdId"]);
                $product->show();

        }
        if (isset($_GET["ctgId"])) {


                $typeDAO = new TypeDAO();
                $types = $typeDAO->getTypesFromCategorieId($_GET["ctgId"]);


            foreach ($types as $type) {
                $typeObject = new Type($type["id"], $type["name"], $type["categorie_id"]);
                $typeObject->show();
            }
        }
         if (isset($_GET["typId"])) {
             $checkType = TypeDAO::existsType($_GET["typId"]);
             if ($checkType["count"] > 0) {

                     $productDAO = new ProductDAO();
                     $products = $productDAO->getProductsFromTypeId($_GET["typId"]);
                     $typeDAO = new TypeDAO();
                     $type = $typeDAO->getTypeInformation($_GET["typId"]);
                     include_once "view/showProductsFromType.php";

                     foreach ($products as $product) {
                         $productDAO = new ProductDAO();
                         $productList = $productDAO->findProduct($product["id"]);

                         $productList->showByType();
                     }

                 $rrp=99;
                 if(isset($_GET["page"])){
                     $page=$_GET["page"];

                 }else{
                     $page=0;
                 }

                 if($page>1){
                     $start=($page*$rrp)-$rrp;

                 }else{
                     $start=0;
                 }


                     $typeDAO=new TypeDAO();
                     $resultSet=$typeDAO->getNumberOfProductsForType($_GET["typId"]);
                     $numRows=$resultSet->count;
                     $typeDAO=new TypeDAO();
                     $products=$typeDAO->getAllByType($_GET["typId"],$start,$rrp);
                     $filters=$this->getFilters($_GET["typId"]);
                     $totalPages=$numRows/$rrp;

             }
             else{
                 header("Location: index.php?target=main&action=render");
             }


            include_once "View/showProductByType.php";
        }
    }

    public function getFilters($id){



            $typeDAO=new TypeDAO();
            $typeNames=$typeDAO->getAttributesByType($id);

            $filter=new Filter();
            $filter->setFilterNames($typeNames);
            $filter->setFilterValues($typeNames);

            return $filter;


    }

    public function add()
    {
        UserController::validateForAdmin();

        $msg = '';
        if (isset($_POST["save"])) {

            if (empty($_POST["name"]) || empty($_POST["producer_id"])
                || empty($_POST["price"]) || empty($_POST["type_id"])
                || empty($_POST["quantity"])) {

                $msg = "All fields are required!";
            } else{
                if (!is_numeric($_POST["quantity"]) || $_POST["quantity"]  <=0 || $_POST["quantity"] != round($_POST["quantity"])) {
                    $msg = "Invalid quantity format!";
                }

                if ($msg == "") {
                    $msg = $this->validatePrice($_POST["price"]);
                }

                if (!is_uploaded_file($_FILES["file"]["tmp_name"])) {

                    $msg = "Image is not uploaded!";
                } elseif ($msg == "") {
                    $file_name_parts = explode(".", $_FILES["file"]["name"]);
                    $extension = $file_name_parts[count($file_name_parts) - 1];
                    $filename = time() . "." . $extension;
                    $img_url = "images" . DIRECTORY_SEPARATOR . $filename;
                    if (!move_uploaded_file($_FILES["file"]["tmp_name"], $img_url)) {

                        $msg = "Image error!";
                    }
                }
                if ($msg == "") {

                    $productDAO = new ProductDAO();
                    $productDAO->add($_POST["name"], $_POST["producer_id"], $_POST["price"], $_POST["type_id"], $_POST["quantity"], $img_url);
                    $msg = "Product added successfully!";
                }else{
                    throw new BadRequestException("$msg");
                }

            }
        }
        include_once "view/addProduct.php";
    }
    public function productNameValidation($name){
        $err=false;
        if(!ctype_alpha($name) || strlen($name) < 2){
            $err=true;
        }
        return $err;
    }

    public function edit(){

        if (isset($_POST["saveChanges"])) {
            $msg = "";
            if (empty($_POST["name"]) || empty($_POST["producer_id"])
                || empty($_POST["price"]) || empty($_POST["type_id"])
                || empty($_POST["quantity"])) {
                $msg = "All fields are required!";
            } elseif($this->validateQuantity($_POST["quantity"])) {
                $msg = "Invalid quantity format!";
            }else{

                if ($msg == "") {
                    $price = $_POST["price"];
                    $old_price = NULL;
                    if (isset($_POST["newPrice"]) && !$this->validatePrice($_POST["newPrice"])) {
                        if ($_POST["newPrice"] >= $_POST["price"]) {
                            $msg = "New price of product must be lower than price !";
                        }else{
                            $price = $_POST["newPrice"];
                            $old_price = $_POST["price"];
                        }


                    }
                }else{
                    throw new BadRequestException("$msg");
                }

                if ($this->validatePrice($_POST["price"])) {
                    throw new BadRequestException("Invalid price!");
                }


                if (!is_uploaded_file($_FILES["file"]["tmp_name"])) {
                    $img_url = $_POST["old_image"];
                } else {
                    $file_name_parts = explode(".", $_FILES["file"]["name"]);
                    $extension = $file_name_parts[count($file_name_parts) - 1];
                    $filename = time() . "." . $extension;
                    $img_url = "images" . DIRECTORY_SEPARATOR . $filename;
                    if (move_uploaded_file($_FILES["file"]["tmp_name"], $img_url)) {
                        unlink($_POST["old_image"]);
                    } else {
                        $msg = "Image error!";
                    }
                }
                if ($msg == "") {
                    $product = [];
                    $product["product_id"] = $_POST["product_id"];
                    $product["name"] = $_POST["name"];
                    $product["producer_id"] = $_POST["producer_id"];
                    $product["price"] = $price;
                    $product["old_price"] = $old_price;
                    $product["type_id"] = $_POST["type_id"];
                    $product["quantity"] = $_POST["quantity"];
                    $product["image_url"] = $img_url;


                    $productDAO = new ProductDAO();
                    $productDAO->edit($product);
                    if (!empty($_POST["newPrice"])){
                        $this->sendPromotionEmail($product["product_id"], $product["name"]);
                    }


                }else{
                    throw new BadRequestException("$msg");
                }

            }

        }

        if(isset($_POST["product_id"])){
            $productId = $_POST["product_id"];
            include_once "view/editProduct.php";
        }else{
            header("Location:index.php?target=product&action=main");
        }

    }

    public function validatePrice($price)
{
    $err=false;
    if (!preg_match('/^[0-9]+(\.[0-9]{1,2})?$/', $price) || !is_numeric($price)) {
        $err = true;
    }
    return $err;
}

    public function validateQuantity($quantity)
    {
        $err=false;
        if (!is_numeric($quantity) || $quantity  <=0 || $quantity != round($quantity)) {
            $err=true;
        }

        return $err;
    }

    public function checkIfIsInPromotion($product_id)
    {
        $productDAO = new ProductDAO();
        $product = $productDAO->getById($product_id);

        $oldPrice = null;
        $inPromotion = false;
        $discount = null;
        if ($product["old_price"] != NULL) {
            $inPromotion = true;
            $oldPrice = $product["old_price"];
            $discount = round((($product["old_price"] - $product["price"]) / $product["old_price"]) * 100, 0);
        }


        $isInStock = null;
        if ($product["quantity"] == 0) {
            $isInStock = "Not available";
        } elseif ($product["quantity"] <= 10) {
            $isInStock = "Limited quantity";
        } elseif ($product["quantity"] > 10) {
            $isInStock = "In stock";
        }

        $status = [];
        $status["in_promotion"] = $inPromotion;
        $status["old_price"] = $oldPrice;
        $status["discount"] = $discount;
        $status["is_in_stock"] = $isInStock;
        return $status;
    }


    public function removeDiscount()
    {
        UserController::validateForAdmin();
        if (isset($_POST["remove"])) {
            if (isset($_POST["product_id"]) && isset($_POST["product_old_price"])) {
                if ($_POST["product_old_price"] != NULL) {
                    $productDAO = new ProductDAO();
                    $productDAO->removePromotion($_POST["product_id"], $_POST["product_old_price"]);
                }

                $productId = $_POST["product_id"];
                include_once "view/editProduct.php";
            }
        }


    }

    public function addProduct()
    {
        UserController::validateForAdmin();
        include_once "view/addProduct.php";
    }

    public function getProducers(){
        $productDAO=new ProductDAO();
        return $productDAO->getProducers();
    }

    public function getTypes(){
        $productDAO=new ProductDAO();
        return $productDAO->getTypes();
    }
    public function getProductById($productId){
        $productDAO=new ProductDAO();
        return $productDAO->getById($productId);
    }


    public function editProduct()
    {
        UserController::validateForAdmin();
        if (isset($_POST["editProduct"])) {
            if (isset($_POST["product_id"])) {
                $productId = $_POST["product_id"];
                include_once "view/editProduct.php";
            } else {
                include_once "view/main.php";


            }
        } else {
            include_once "view/main.php";
        }
    }


    public function showProduct()
    {
        include_once "view/showProduct.php";

    }

    public function filterProducts()
    {
                $counter = 0;
                $filters = $_POST["checked"];
                $msg = "";
                $args = [];
                error_log(json_encode($_POST["checked"]));
            if (isset($_POST["checked"])){

                foreach ($_POST["checked"] as $filter){
                    $name = $filter["name"];
                    $checked = $filter["checkedValues"];
                    $params = array_map(function ($el){return "?";}, $checked);
                    $stringParams = implode(',',$params);

                    $alias = "attr$counter";
                    if ($counter == 0){
                        $msg.= "SELECT * FROM (
                                SELECT distinct  p.name , p.id , p.price , p.quantity , p.image_url 
                                FROM products as p 
                                JOIN product_attributes as pha ON (p.id = pha.product_id)
                                JOIN attributes as a ON (pha.attribute_id = a.id) 
                                WHERE p.type_id = 1
                                AND  a.name=? AND pha.value in($stringParams)) as $alias";

                        $args[].= $name;
                        $args = array_merge($args, $checked);
                    }else {
                        $prevIndex = $counter - 1;
                        $prevAlias = "attr$prevIndex";
                        $msg.=" join (
                            SELECT distinct p.id 
                            FROM products as p
                            JOIN product_attributes as pha ON (p.id = pha.product_id)
                            JOIN attributes as a ON (pha.attribute_id = a.id) 
                            WHERE p.type_id = 1
                            AND  a.name=? AND pha.value in($stringParams)
                            ) as $alias on $prevAlias.id = $alias.id";

                        $args[].= $name;
                        $args = array_merge($args, $checked);
                    }

                    ++$counter;
                }
                $msg.= ";";
                $filter = new ProductDAO();
                $filter->filterProducts($msg , $args);

            }
    }

    public function sendPromotionEmail($productId, $productName)
    {
        $productDAO = new ProductDAO();
        $emails = $productDAO->getUserEmailsByLikedProduct($productId);
        foreach ($emails as $email) {
            $this->sendemail($email["email"], $productName , $productId);
        }

    }

    public function getMostCelledProducts(){
        $productDAO = new ProductDAO();
        return $products=$productDAO->getMostSold();

    }
 public function main(){

        include_once "view/main.php";

    }
    public function getAttributes($product_id){
        $productDAO = new ProductDAO();
        return $attributes=$productDAO->getProductAttributesById($product_id);

    }

    function sendemail($email, $productName , $productId)
        {

            require_once "PHPMailer-5.2-stable/PHPMailerAutoload.php";
            $mail = new PHPMailer;
//$mail->SMTPDebug = 3;                               // Enable verbose debug output
            $mail->isSMTP();
            $mail->SMTPDebug = 0;// Set mailer to use SMTP
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Host = 'smtp.sendgrid.net';  // Specify main and backup SMTP servers
            $mail->Username = EMAIL_USERNAME;                 // SMTP username
            $mail->Password = EMAIL_PASSWORD;                           // SMTP password
            $mail->SMTPSecure = 'tsl';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            $mail->setFrom('emag9648@gmail.com');
            $mail->addAddress($email);     // Add a recipient
            $mail->isHTML(true);                                  // Set email format to HTML

            $mail->Subject = 'Your Product is on Sale !!!';
            $mail->Body = "$productName Product is in Sale Now !!! Go Check it out before the sale expires <a href = http://localhost:8888/It-talents/index.php?target=product&action=show&prdId=$productId>Open Here</a>";
            $mail->AltBody = 'Click For Register';


            if (!$mail->send()) {
                echo 'Message could not be sent.';
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                echo 'Message has been sent';
            }
        }
}

