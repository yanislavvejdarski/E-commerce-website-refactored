<?php

namespace model\DAO;

use model\DBManager;
use PDO;

class AbstractDAO
{
    /**
     * @var instance
     */
    public $pdo;

    /**
     * AbstractDAO constructor.
     */
    public function __construct()
    {
        $this->pdo = DBManager::getInstance()->getPDO();
    }

    /**
     * @param $sql string
     * @param null | array
     *
     * @return bool|PDOStatement
     */
    public function prepareAndExecute(
        $sql,
        $params = []
    ) {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $statement;
    }

    /**
     * @param $sql string
     * @param $params array
     *
     * @return array
     */
    public function fetchAllObject(
        $sql,
        $params = []
    ) {
        $statement = $this->prepareAndExecute(
            $sql,
            $params
        );

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param $sql string
     * @param $params array
     *
     * @return array
     */
    public function fetchAllAssoc(
        $sql,
        $params = []
    ) {
        $statement = $this->prepareAndExecute(
            $sql,
            $params
        );

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param $sql string
     * @param $params array
     *
     * @return mixed
     */
    public function fetchOneAssoc(
        $sql,
        $params = []
    ) {
        $statement = $this->prepareAndExecute(
            $sql,
            $params
        );

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param $sql string
     * @param $params array
     *
     * @return mixed
     */
    public function fetchOneObject(
        $sql,
        $params = []
    ) {
        $statement = $this->prepareAndExecute(
            $sql,
            $params
        );

        return $statement->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @return int
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    /**
     *  Rollback
     */
    public function rollback()
    {
        $this->pdo->rollBack();
    }

    /**
     * Begin Transaction
     */
    public function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }

    /**
     * Commit
     */
    public function commit()
    {
        $this->pdo->commit();
    }
}