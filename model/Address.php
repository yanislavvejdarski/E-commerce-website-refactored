<?php
namespace model;

class Address
{
private $id;
private $user_id;
private $city_id;
private $street_name;

public function __construct($user_id,$city_id,$street_name){
    $this->user_id=$user_id;
    $this->city_id=$city_id;
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
        return $this->city_id;
    }

    public function getStreetName()
    {
        return $this->street_name;
    }


    public function getUserId()
    {
        return $this->user_id;
    }
}