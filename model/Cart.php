<?php
namespace model;
class Cart{
    private $userId;
    private $productId;
    private $quantity;

    function __construct($userId , $productId, $quantity)
    {
        $this->userId = $userId;
        $this->productId = $productId;
        $this->quantity = $quantity;
    }
    public function getProductId()
    {
        return $this->productId;
    }
    public function getQuantity()
    {
        return $this->quantity;
    }
    public function getUserId()
    {
        return $this->userId;
    }
}