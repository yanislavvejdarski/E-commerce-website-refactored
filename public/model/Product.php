<?php

namespace model;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Product{
public $id;
public $name;
protected $producerId;
public $price;
protected $typeId;
public $quantity;
public $imageUrl;


function __construct($id , $name , $producerId , $price , $typeId , $quantity ,$imageUrl)
    {
        $this->id=$id;
        $this->name=$name;
        $this->producerId = $producerId;
        $this->price=$price;
        $this->typeId=$typeId;
        $this->quantity=$quantity;
        $this->imageUrl=$imageUrl;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }

    public function getProducerId()
    {
        return $this->producerId;
    }

    public function getTypeId()
    {
        return $this->typeId;
    }

    function show(){
        include "view/showProduct.php";
    }


    function showByType(){
        include "view/showProductByType.php";

    }


}