<?php
namespace model;

class Address
{
private $id;
private $userId;
private $cityId;
private $streetName;

    /**
     * Address constructor.
     * @param int $userId
     * @param int $cityId
     * @param string $streetName
     */
public function __construct(
    $userId,
    $cityId,
    $streetName
) {
    $this->userId = $userId;
    $this->cityId = $cityId;
    $this->streetName = $streetName;
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
    public function getCityId()
    {

        return $this->cityId;
    }

    /**
     * @return string
     */
    public function getStreetName()
    {

        return $this->streetName;
    }

    /**
     * @return int
     */
    public function getUserId()
    {

        return $this->userId;
    }
}