<?php
namespace model;

class Address
{
private $id;
private $userId;
private $cityId;
private $street_name;

public function __construct($userId,$cityId,$street_name){
    $this->userId=$userId;
    $this->cityId=$cityId;
    $this->street_name=$street_name;
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
        return $this->street_name;
    }


    public function getUserId()
    {
        return $this->userId;
    }
}