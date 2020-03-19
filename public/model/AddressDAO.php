<?php

namespace model;
use PDO;

class AddressDAO extends AbstractDAO {

    /**
     * @param $id
     *
     * @return object
     */
    public function getById($id) {
        $params = [];
        $params[] = $id;
        $sql="SELECT a.id, a.city_id,a.user_id, c.name AS city_name,a.street_name 
              FROM addresses AS a JOIN cities AS c ON(a.city_id=c.id)WHERE a.id=?;";

        return $this->fetchOneObject(
            $sql,
            $params
        );
    }

    /**
     * @return array
     */
    public function getCities() {
        $sql="SELECT * FROM cities;";

        return $this->fetchAllAssoc($sql);
    }

    /**
     * @param $address object
     */
    public function add(Address $address) {
        $params = [];
        $params[] = $address->getUserId();
        $params[] = $address->getCityId();
        $params[] = $address->getStreetName();
        $sql = "INSERT INTO addresses (user_id, city_id, street_name,date_created) VALUES (?, ?, ?,now());";
        $this->prepareAndExecute(
            $sql,
            $params
        );
        $address->setId($this->lastInsertId());
    }

    /**
     * @param $user_id int
     *
     * @return array
     */
    public function getAll($user_id) {
        $params = [];
        $params[] = $user_id;
        $sql = "SELECT a.id, c.name AS city_name,a.street_name 
                FROM addresses AS a JOIN cities AS c ON(a.city_id=c.id)WHERE user_id=?;";

        return $this->fetchAllObject(
            $sql,
            $params
        );
    }

    /**
     * @param $address object
     */
    public function edit(Address $address) {
        $params = [];
        $params[] = $address->getCityId();
        $params[] = $address->getStreetName();
        $params[] = $address->getId();
        $sql = "UPDATE addresses SET city_id=?, street_name=? WHERE id=? ;";
        $this->prepareAndExecute(
            $sql,
            $params
        );
    }

    /**
     * @param $id int
     */
    public function delete($id) {
        $params = [];
        $params[] = $id;
        $sql = "DELETE FROM addresses WHERE id=? ;";
        $this->prepareAndExecute(
            $sql,
            $params
        );
    }

    /**
     * @param $userId int
     *
     * @return array
     */
      public function userAddress($userId) {
        $params = [];
        $params[] = $userId;
        $sql = "SELECT id FROM addresses WHERE user_id = ?";

        return $this->fetchAllObject(
            $sql,
            $params
        );
      }
}