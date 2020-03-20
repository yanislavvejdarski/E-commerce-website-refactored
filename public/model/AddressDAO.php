<?php

namespace model;
use PDO;

class AddressDAO extends AbstractDAO
{
    /**
     * @param $id int
     *
     * @return array
     */
    public function getById($id)
    {
        $params = [];
        $params["id"] = $id;
        $sql='
            SELECT 
                a.id,
                a.city_id,
                a.user_id,
                c.NAME AS city_name,
                a.street_name
            FROM   
                addresses AS a 
                JOIN cities AS c ON( a.city_id = c.id )
            WHERE  
                a.id = :id ;';

        return $this->fetchOneObject(
            $sql,
            $params
        );
    }

    /**
     * @return array
     */
    public function getCities()
    {
        $sql="SELECT * FROM cities;";

        return $this->fetchAllAssoc($sql);
    }

    /**
     * @param Address $address
     */
    public function add(Address $address)
    {
        $params = [];
        $params["userId"] = $address->getUserId();
        $params["cityId"] = $address->getCityId();
        $params["streetName"] = $address->getStreetName();
        $sql = '
            INSERT INTO 
                addresses 
                (user_id,
                 city_id,
                 street_name,
                 date_created) 
            VALUES 
                (:userId,
                 :cityId,
                 :streetName,
                 now())
                 ;';
        $this->prepareAndExecute(
            $sql,
            $params
        );
        $address->setId($this->lastInsertId());
    }

    /**
     * @param int $userId
     *
     * @return array
     */
    public function getAll($userId)
    {
        $params = [];
        $params["userId"] = $userId;
        $sql = '
            SELECT 
                a.id,
                c.name AS city_name,
                a.street_name 
            FROM 
                addresses AS a 
                JOIN cities AS c ON(a.city_id = c.id)
            WHERE user_id = :userId
                ;';

        return $this->fetchAllObject(
            $sql,
            $params
        );
    }

    /**
     * @param Address $address
     */
    public function edit(Address $address)
    {
        $params = [];
        $params["cityId"] = $address->getCityId();
        $params["streetName"] = $address->getStreetName();
        $params["addressId"] = $address->getId();
        $sql = '
            UPDATE 
                addresses 
            SET 
                city_id = :cityId,
                street_name = :streetName 
            WHERE 
                id = :addressId 
                ;';
        $this->prepareAndExecute(
            $sql,
            $params
        );
    }

    /**
     * @param int $id
     */
    public function delete($id)
    {
        $params = [];
        $params["id"] = $id;
        $sql = '
            DELETE FROM 
                addresses 
            WHERE 
                id = :id 
                ;';
        $this->prepareAndExecute(
            $sql,
            $params
        );
    }

    /**
     * @param int $userId
     *
     * @return array
     */
      public function userAddress($userId)
      {
        $params = [];
        $params["userId"] = $userId;
        $sql = '
            SELECT 
                id 
            FROM 
                addresses 
            WHERE 
                user_id = :userId
                ';

        return $this->fetchAllObject(
            $sql,
            $params
        );
      }
}