<?php
namespace model;
class Order{
    private $userId;
    private $addressId;

    public function __construct($userId, $addressId)
    {
        $this->userId = $userId;
        $this->addressId = $addressId;
    }

    public function getUserId()
    {
        return $this->userId;
    }
    public function getAddressId()
    {
        return $this->addressId;
    }
}