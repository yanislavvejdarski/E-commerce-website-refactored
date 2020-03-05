<?php
namespace model;
class Favourite{
    private $userId;
    private $productId;

    public function __construct($userId , $productId)
    {
        $this->userId = $userId;
        $this->productId = $productId;
    }

    public function getUserId()
    {
        return $this->userId;
    }
    public function getProductId()
    {
        return $this->productId;
    }
}