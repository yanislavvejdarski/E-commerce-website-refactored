<?php

namespace model;
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
use model\DAO\ProductDAO;
class Product
{
public $id;
public $name;
protected $producerId;
public $price;
protected $typeId;
public $quantity;
public $imageUrl;

    /**
     * Product constructor.
     * @param int $id
     * @param string $name
     * @param int $producerId
     * @param float $price
     * @param int $typeId
     * @param int $quantity
     * @param string $imageUrl
     */
function __construct($id , $name , $producerId , $price , $typeId , $quantity ,$imageUrl)
    {
        $this->id=$id;
        $this->name=$name;
        $this->producerId = $producerId;
        $this->price=$price;
        $this->typeId=$typeId;
        $this->quantity=$quantity;
        $this->imageUrl='../' . $imageUrl;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * @return int
     */
    public function getProducerId()
    {

        return $this->producerId;
    }

    /**
     * @return int
     */
    public function getTypeId()
    {

        return $this->typeId;
    }

    /**
     * Include Product Page
     */
    function show(){
        include "view/showProduct.php";
    }

    /**
     * Include Show Product By Type Page
     */
    function showByType(){
        include "view/showProductByType.php";

    }
}