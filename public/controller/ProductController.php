<?php

namespace controller;

use exception\BadRequestException;
use model\Filter;
use model\DAO\ProductDAO;
use model\Type;
use model\DAO\TypeDAO;
use PHPMailer;
use helpers\Request;

include_once "credentials.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ProductController extends AbstractController
{
    public function show()
    {
        $getParams = $this->request->getParams();
        if (isset($getParams["product"])) {
            $productDAO = new ProductDAO();
            $product = $productDAO->findProduct($getParams["product"]);
            $product->show();
        }
        if (isset($getParams["ctgId"])) {

            $typeDAO = new TypeDAO();
            $types = $typeDAO->getTypesFromCategorieId($getParams["ctgId"]);
            foreach ($types as $type) {
                $typeObject = new Type($type["id"], $type["name"], $type["categorie_id"]);
                $typeObject->show();
            }
        }
        if (isset($getParams["typeId"])) {
            $typeDAO = new TypeDAO();
            $checkType = $typeDAO->existsType($getParams["typeId"]);
            if ($checkType["count"] > 0) {

                $productDAO = new ProductDAO();
                $products = $productDAO->getProductsFromTypeId($getParams["typeId"]);
                $type = $typeDAO->getTypeInformation($getParams["typeId"]);
                include_once "view/showProductsFromType.php";
                $typeDAO = new TypeDAO();
                $resultSet = $typeDAO->getNumberOfProductsForType($getParams["typeId"]);
                $numRows = $resultSet->count;
                $typeDAO = new TypeDAO();
                $products = $typeDAO->getAllByType($getParams["typeId"]);
                $filters = $this->getFilters($getParams["typeId"]);

            } else {
                header("Location: /home");
            }
            include_once "view/showProductByType.php";
        }
    }

    public function getFilters($id)
    {
        $typeDAO = new TypeDAO();
        $typeNames = $typeDAO->getAttributesByType($id);
        $filter = new Filter();
        $filter->setFilterNames($typeNames);
        $filter->setFilterValues($typeNames);

        return $filter;
    }

    public function addProduct()
    {
        UserController::validateForAdmin();
        $postParams = $this->request->postParams();
        $msg = '';
        if (isset($postParams["save"])) {

            if (empty($postParams["name"]) || empty($postParams["producer_id"])
                || empty($postParams["price"]) || empty($postParams["type_id"])
                || empty($postParams["quantity"])) {

                $msg = "All fields are required!";
            } else {
                if (!is_numeric($postParams["quantity"]) || $postParams["quantity"] <= 0 || $postParams["quantity"] != round($postParams["quantity"])) {
                    $msg = "Invalid quantity format!";
                }

                if ($msg == "") {
                    $msg = $this->validatePrice($postParams["price"]);
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
                    $productDAO->add($postParams["name"], $postParams["producer_id"], $postParams["price"], $postParams["type_id"], $postParams["quantity"], $img_url);
                    $msg = "Product added successfully!";
                } else {
                    throw new BadRequestException("$msg");
                }
            }
        }
        include_once "view/addProduct.php";
    }

    public function productNameValidation($name)
    {
        $err = false;
        if (!ctype_alpha($name) || strlen($name) < 2) {
            $err = true;
        }
        return $err;
    }

    public function editProduct()
    {
        $postParams = $this->request->postParams();
        if (isset($postParams["saveChanges"])) {
            $msg = "";
            if (empty($postParams["name"]) || empty($postParams["producer_id"])
                || empty($postParams["price"]) || empty($postParams["type_id"])
                || empty($postParams["quantity"])) {
                $msg = "All fields are required!";
            } elseif ($this->validateQuantity($postParams["quantity"])) {
                $msg = "Invalid quantity format!";
            } else {
                if ($msg == "") {
                    $price = $postParams["price"];
                    $old_price = NULL;
                    if (isset($postParams["newPrice"]) && !$this->validatePrice($postParams["newPrice"])) {
                        if ($postParams["newPrice"] >= $postParams["price"]) {
                            $msg = "New price of product must be lower than price !";
                        } else {
                            $price = $postParams["newPrice"];
                            $old_price = $postParams["price"];
                        }
                    }
                } else {
                    throw new BadRequestException("$msg");
                }

                if ($this->validatePrice($postParams["price"])) {
                    throw new BadRequestException("Invalid price!");
                }


                if (!is_uploaded_file($_FILES["file"]["tmp_name"])) {
                    $img_url = $postParams["old_image"];
                } else {
                    $file_name_parts = explode(".", $_FILES["file"]["name"]);
                    $extension = $file_name_parts[count($file_name_parts) - 1];
                    $filename = time() . "." . $extension;
                    $img_url = "images" . DIRECTORY_SEPARATOR . $filename;
                    if (move_uploaded_file($_FILES["file"]["tmp_name"], $img_url)) {
                        unlink($postParams["old_image"]);
                    } else {
                        $msg = "Image error!";
                    }
                }
                if ($msg == "") {
                    $product = [];
                    $product["product_id"] = $postParams["product_id"];
                    $product["name"] = $postParams["name"];
                    $product["producer_id"] = $postParams["producer_id"];
                    $product["price"] = $price;
                    $product["old_price"] = $old_price;
                    $product["type_id"] = $postParams["type_id"];
                    $product["quantity"] = $postParams["quantity"];
                    $product["image_url"] = $img_url;
                    $productDAO = new ProductDAO();
                    $productDAO->edit($product);
                    if (!empty($postParams["newPrice"])) {
                        $this->sendPromotionEmail($product["product_id"], $product["name"]);
                    }
                } else {
                    throw new BadRequestException("$msg");
                }
            }
        }
        if (isset($postParams["product_id"])) {
            $productId = $postParams["product_id"];
            include_once "view/editProduct.php";
        } else {
            header("Location:/home");
        }
    }

    public function validatePrice($price)
    {
        $err = false;
        if (!preg_match('/^[0-9]+(\.[0-9]{1,2})?$/', $price) || !is_numeric($price)) {
            $err = true;
        }
        return $err;
    }

    public function validateQuantity($quantity)
    {
        $err = false;
        if (!is_numeric($quantity) || $quantity <= 0 || $quantity != round($quantity)) {
            $err = true;
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
        $postParams = $this->request->postParams();
        if (isset($postParams["remove"])) {
            if (isset($postParams["product_id"]) && isset($postParams["product_old_price"])) {
                if ($postParams["product_old_price"] != NULL) {
                    $productDAO = new ProductDAO();
                    $productDAO->removePromotion($postParams["product_id"], $postParams["product_old_price"]);
                }
                $productId = $postParams["product_id"];
                include_once "view/editProduct.php";
            }
        }
    }

    public function addProductPage()
    {
        UserController::validateForAdmin();
        include_once "view/addProduct.php";
    }

    public function getProducers()
    {
        $productDAO = new ProductDAO();
        return $productDAO->getProducers();
    }

    public function getTypes()
    {
        $productDAO = new ProductDAO();
        return $productDAO->getTypes();
    }

    public function getProductById($productId)
    {
        $productDAO = new ProductDAO();
        return $productDAO->getById($productId);
    }


    public function editProductPage()
    {
        UserController::validateForAdmin();
        $postParams = $this->request->postParams();
        if (isset($postParams["editProduct"])) {
            if (isset($postParams["product_id"])) {
                $productId = $postParams["product_id"];
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
        $filters = $this->request->postParam("checked");
        $msg = "";
        $args = [];
        error_log(json_encode($this->request->postParams()));
        if (!empty($this->request->postParam("checked"))) {

            foreach ($this->request->postParam("checked") as $filter) {
                $name = $filter["name"];
                $checked = $filter["checkedValues"];
                $paramas = array_map(function ($el) {
                    return "?";
                }, $checked);
                $stringParams = implode(',', $paramas);

                $alias = "attr$counter";
                if ($counter == 0) {
                    $msg .= "SELECT * FROM (
                                SELECT distinct  p.name , p.id , p.price , p.quantity , p.image_url 
                                FROM products as p 
                                JOIN product_attributes as pha ON (p.id = pha.product_id)
                                JOIN attributes as a ON (pha.attribute_id = a.id) 
                                WHERE p.type_id = 1
                                AND  a.name=? AND pha.value in($stringParams)) as $alias";
                    $args[] .= $name;
                    $args = array_merge($args, $checked);
                } else {
                    $prevIndex = $counter - 1;
                    $prevAlias = "attr$prevIndex";
                    $msg .= " join (
                            SELECT distinct p.id 
                            FROM products as p
                            JOIN product_attributes as pha ON (p.id = pha.product_id)
                            JOIN attributes as a ON (pha.attribute_id = a.id) 
                            WHERE p.type_id = 1
                            AND  a.name=? AND pha.value in($stringParams)
                            ) as $alias on $prevAlias.id = $alias.id";

                    $args[] .= $name;
                    $args = array_merge($args, $checked);
                }

                ++$counter;
            }
            $msg .= ";";
            $filter = new ProductDAO();
            $filter->filterProducts($msg, $args);
        }
    }

    public function sendPromotionEmail($productId, $productName)
    {
        $productDAO = new ProductDAO();
        $emails = $productDAO->getUserEmailsByLikedProduct($productId);
        foreach ($emails as $email) {
            $this->sendemail($email["email"], $productName, $productId);
        }

    }

    public function getMostCelledProducts()
    {
        $productDAO = new ProductDAO();
        return $products = $productDAO->getMostSold();

    }

    public function main()
    {

        include_once "view/main.php";

    }

    public function getAttributes($product_id)
    {
        $productDAO = new ProductDAO();
        return $attributes = $productDAO->getProductAttributesById($product_id);

    }

    function sendemail($email, $productName, $productId)
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
        $mail->Body = "$productName Product is in Sale Now !!! Go Check it out before the sale expires <a href = http://localhost:8888/It-talents//product/$productId>Open Here</a>";
        $mail->AltBody = 'Click For Register';


        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
    }
}

