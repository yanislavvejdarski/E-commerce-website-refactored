<?php

namespace controller;

use exception\BadRequestException;
use model\Filter;
use model\DAO\ProductDAO;
use model\Type;
use model\DAO\TypeDAO;
use PHPMailer;
use helpers\Request;
use phpmailerException;

include_once 'credentials.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ProductController extends AbstractController
{
    /**
     * Show Product
     */
    public function showProduct()
    {
        $getParams = $this->request->getParams();
        $paramsAndRules = [
            $getParams['product'] => 'isVariableSet'
        ];
        if ($this->validator->validate($paramsAndRules)) {
            $productDAO = new ProductDAO();
            $product = $productDAO->findProduct($getParams['product']);
            $product->show();
        }
    }

    /**
     * Show Category
     */
    public function showCategory()
    {
        $getParams = $this->request->getParams();
        $paramsAndRules = [
            $getParams['ctgId'] => 'isVariableSet'
        ];
        if ($this->validator->validate($paramsAndRules)) {
            $typeDAO = new TypeDAO();
            $types = $typeDAO->getTypesFromCategorieId($getParams['ctgId']);
            foreach ($types as $type) {
                $typeObject = new Type(
                    $type['id'],
                    $type['name'],
                    $type['categorie_id']
                );
                $typeObject->show();
            }
        }
    }

    /**
     * Show Type
     */
    public function showType()
    {
        $getParams = $this->request->getParams();
        $paramsAndRules = [
            $getParams['typeId'] => 'isVariableSet'
        ];
        if ($this->validator->validate($paramsAndRules)) {
            $typeDAO = new TypeDAO();
            $checkType = $typeDAO->existsType($getParams['typeId']);
            if ($checkType['count'] > 0) {
                $productDAO = new ProductDAO();
                $products = $productDAO->getProductsFromTypeId($getParams['typeId']);
                $type = $typeDAO->getTypeInformation($getParams['typeId']);
                include_once 'view/showProductsFromType.php';
                $typeDAO = new TypeDAO();
                $resultSet = $typeDAO->getNumberOfProductsForType($getParams['typeId']);
                $numRows = $resultSet->count;
                $typeDAO = new TypeDAO();
                $products = $typeDAO->getAllByType($getParams['typeId']);
                $filters = $this->getFilters($getParams['typeId']);
            } else {
                header('Location: /home');
            }
            include_once 'view/showProductByType.php';
        }
    }

    /**
     * @param int $id
     *
     * @return Filter
     */
    public function getFilters($id)
    {
        $typeDAO = new TypeDAO();
        $typeNames = $typeDAO->getAttributesByType($id);
        $filter = new Filter();
        $filter->setFilterNames($typeNames);
        $filter->setFilterValues($typeNames);

        return $filter;
    }

    /**
     * @throws BadRequestException
     */
    public function addProduct()
    {
        $postParams = $this->request->postParams();
        $paramsAndRules = [
            $postParams['save'] => 'isVariableSet',
            $postParams['name'] => 'isEmpty',
            $postParams['producerId'] => 'isEmpty',
            $postParams['price'] => 'isEmpty',
            $postParams['typeId'] => 'isEmpty',
            $postParams['quantity'] => 'isEmpty|isNumeric|biggerThan:0'
        ];
        if ($this->validator->validate($paramsAndRules) && $this->validateProductPrice($postParams['price'])) {
            $msg = '';
            if (!is_uploaded_file($_FILES['file']['tmp_name'])) {
                $msg = 'Image is not uploaded!';
            } elseif ($msg == '') {
                $file_name_parts = explode('.', $_FILES['file']['name']);
                $extension = $file_name_parts[count($file_name_parts) - 1];
                $filename = time() . '.' . $extension;
                $img_url = 'images' . DIRECTORY_SEPARATOR . $filename;
                if (!move_uploaded_file($_FILES['file']['tmp_name'], $img_url)) {
                    $msg = 'Image error!';
                }
            }
            if ($msg == '') {
                $productDAO = new ProductDAO();
                $productDAO->add(
                    $postParams['name'],
                    $postParams['producerId'],
                    $postParams['price'],
                    $postParams['typeId'],
                    $postParams['quantity'],
                    $img_url
                );
                $msg = 'Product added successfully!';
            } else {
                throw new BadRequestException("Couldn't add product");
            }

        }
        include_once 'view/addProduct.php';
    }

    /**
     * @param float $price
     *
     * @return bool
     */
    public function validateProductPrice($price)
    {
        return !preg_match('/^[0-9]+(\.[0-9]{1,2})?$/', $price) ? true : false;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function productNameValidation($name)
    {
        $paramsAndRules = [
            $name => 'isAlphabetic|biggerThan:2'
        ];
        if ($this->validator->validate($paramsAndRules)) {
            return true;
        }
        return false;
    }

    /**
     * @throws BadRequestException
     */
    public function editProduct()
    {
        $msg = '';
        $postParams = $this->request->postParams();
        $paramsAndRules = [
            $postParams['saveChanges'] => 'isVariableSet',
            $postParams['name'] => 'isEmpty',
            $postParams['producerId'] => 'isEmpty',
            $postParams['price'] => 'isEmpty',
            $postParams['typeId'] => 'isEmpty',
            $postParams['quantity'] => 'isEmpty|isNumeric|biggerThan:0',
            $postParams['newPrice'] => 'isVariableSet',
            $postParams['productId'] => 'isVariableSet'
        ];
        if ($this->validator->validate($paramsAndRules)) {
            $price = $postParams['price'];
            $old_price = NULL;
            if ($postParams['newPrice'] >= $postParams['price']) {
                $msg = 'New price of product must be lower than price !';
            } else {
                $price = $postParams['newPrice'];
                $old_price = $postParams['price'];
            }
            if (!is_uploaded_file($_FILES['file']['tmp_name'])) {
                $img_url = $postParams['oldImage'];
            } else {
                $file_name_parts = explode('.', $_FILES['file']['name']);
                $extension = $file_name_parts[count($file_name_parts) - 1];
                $filename = time() . '.' . $extension;
                $img_url = 'images' . DIRECTORY_SEPARATOR . $filename;
                if (move_uploaded_file($_FILES['file']['tmp_name'], $img_url)) {
                    unlink($postParams['old_image']);
                } else {
                    $msg = 'Image error!';
                }
            }
            if ($msg == '') {
                $product = [];
                $product['productId'] = $postParams['productId'];
                $product['name'] = $postParams['name'];
                $product['producerId'] = $postParams['producerId'];
                $product['price'] = $price;
                $product['oldPrice'] = $old_price;
                $product['typeId'] = $postParams['typeId'];
                $product['quantity'] = $postParams['quantity'];
                $product['imageUrl'] = $img_url;
                $productDAO = new ProductDAO();
                $productDAO->edit($product);
                if (!empty($postParams['newPrice'])) {
                    $this->sendPromotionEmail($product['productId'], $product['name']);
                }
                $productId = $postParams['productId'];
                include_once 'view/editProduct.php';
            } else {
                throw new BadRequestException('$msg');
            }
        } else {
            header('Location:/home');
        }
    }

    /**
     * @param int $product_id
     *
     * @return array
     */
    public function checkIfIsInPromotion($product_id)
    {
        $productDAO = new ProductDAO();
        $product = $productDAO->getById($product_id);
        $oldPrice = null;
        $inPromotion = false;
        $discount = null;
        if ($product['oldPrice'] != NULL) {
            $inPromotion = true;
            $oldPrice = $product['oldPrice'];
            $discount = round((($product['oldPrice'] - $product['price']) / $product['oldPrice']) * 100, 0);
        }
        $isInStock = null;
        if ($product['quantity'] == 0) {
            $isInStock = 'Not available';
        } elseif ($product['quantity'] <= 10) {
            $isInStock = 'Limited quantity';
        } elseif ($product['quantity'] > 10) {
            $isInStock = 'In stock';
        }
        $status = [];
        $status['in_promotion'] = $inPromotion;
        $status['old_price'] = $oldPrice;
        $status['discount'] = $discount;
        $status['is_in_stock'] = $isInStock;

        return $status;
    }

    /**
     *  Remove Discount From Product
     */
    public function removeDiscount()
    {
        $postParams = $this->request->postParams();
        $paramsAndRules = [
            $postParams['remove'] => 'isVariableSet',
            $postParams['productOldPrice'] => 'isVariableSet|biggerThan:0',
            $postParams['productId'] => 'isVariableSet'
        ];
        if ($this->validator->validate($paramsAndRules)) {
            $productDAO = new ProductDAO();
            $productDAO->removePromotion(
                $postParams['productId'],
                $postParams['productOldPrice']
            );
            $productId = $postParams['productId'];
            include_once 'view/editProduct.php';
        }
    }

    /**
     * Show addProductPage
     */
    public function addProductPage()
    {
        include_once 'view/addProduct.php';
    }

    /**
     * @return array
     */
    public function getProducers()
    {
        $productDAO = new ProductDAO();

        return $productDAO->getProducers();
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        $productDAO = new ProductDAO();

        return $productDAO->getTypes();
    }

    /**
     * @param int $productId
     *
     * @return array
     */
    public function getProductById($productId)
    {
        $productDAO = new ProductDAO();

        return $productDAO->getById($productId);
    }

    /**
     *  Show Edit Product Page
     */
    public function editProductPage()
    {
        $postParams = $this->request->postParams();
        $paramsAndRules = [
            $postParams['editProduct'] => 'isVariableSet',
            $postParams['productId'] => 'isVariableSet'
        ];
        if ($this->validator->validate($paramsAndRules)) {
            $productId = $postParams['productId'];
            include_once 'view/editProduct.php';
        } else {
            include_once 'view/main.php';
        }

    }

    /**
     *  AJAX Filtration Of Products
     */
    public function filterProducts()
    {
        $counter = 0;
        $filters = $this->request->postParam('checked');
        $msg = '';
        $args = [];
        error_log(json_encode($this->request->postParams()));
        if (is_array($this->request->postParam('checked'))) {
            foreach ($this->request->postParam('checked') as $filter) {
                $name = $filter['name'];
                $checked = $filter['checkedValues'];
                $paramas = array_map(function ($el) {
                    return '?';
                }, $checked);
                $stringParams = implode(',', $paramas);

                $alias = 'attr$counter';
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
            $msg .= ';';
            $filter = new ProductDAO();
            $filter->filterProducts(
                $msg,
                $args
            );
        }
    }

    /**
     * @param int $productId
     * @param string $productName
     */
    public function sendPromotionEmail(
        $productId,
        $productName
    ) {
        $productDAO = new ProductDAO();
        $emails = $productDAO->getUserEmailsByLikedProduct($productId);
        foreach ($emails as $email) {
            $this->sendemail(
                $email['email'],
                $productName,
                $productId
            );
        }
    }

    /**
     * @return array
     */
    public function getMostCelledProducts()
    {
        $productDAO = new ProductDAO();

        return $products = $productDAO->getMostSold();
    }

    /**
     * @param int $product_id
     *
     * @return array
     */
    public function getAttributes($product_id)
    {
        $productDAO = new ProductDAO();

        return $attributes = $productDAO->getProductAttributesById($product_id);
    }

    /**
     * @param string $email
     * @param string $productName
     * @param int $productId
     *
     * @throws phpmailerException
     */
    public function sendemail(
        $email,
        $productName,
        $productId
    ) {
        require_once 'PHPMailer-5.2-stable/PHPMailerAutoload.php';
        $mail = new PHPMailer;
        //$mail->SMTPDebug = 3;                               // Enable verbose debug output
        $mail->isSMTP();
        $mail->SMTPDebug = 0;                                 // Set mailer to use SMTP
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Host = 'smtp.sendgrid.net';                    // Specify main and backup SMTP servers
        $mail->Username = EMAIL_USERNAME;                     // SMTP username
        $mail->Password = EMAIL_PASSWORD;                     // SMTP password
        $mail->SMTPSecure = 'tsl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to
        $mail->setFrom('emag9648@gmail.com');
        $mail->addAddress($email);                            // Add a recipient
        $mail->isHTML(true);                           // Set email format to HTML
        $mail->Subject = 'Your Product is on Sale !!!';
        $mail->Body = '$productName Product is in Sale Now !!! Go Check it out before the sale expires <a href = http://localhost:8888/It-talents//product/$productId>Open Here</a>';
        $mail->AltBody = 'Click For Register';

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
    }
}