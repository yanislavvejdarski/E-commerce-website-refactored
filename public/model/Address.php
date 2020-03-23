<?php
namespace model;

class Address
{
private $id;
private $userId;
private $cityId;
private $streetName;

public function __construct($userId,$cityId,$streetName)
{
    $this->userId = $userId;
    $this->cityId = $cityId;
    $this->streetName = $streetName;
}

    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }

    public function getCityId()
    {
        return $this->cityId;
    }

    public function getStreetName()
    {
        return $this->streetName;
    }


    public function getUserId()
    {
        return $this->userId;
    }
}