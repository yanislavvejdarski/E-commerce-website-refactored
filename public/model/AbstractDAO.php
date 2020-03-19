<?php
namespace model;

use model\DBManager;
use PDO;

class AbstractDAO{
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
     * @param $fetchWay string
     * @param $params array
     *
     * @return array
     */
   public function fetchAll(
       $sql,
       $fetchWay,
       $params = []
   ) {
       $statement = $this->prepareAndExecute(
           $sql,
           $params
       );
       if ($fetchWay == "fetchAssoc") {

           return $statement->fetchAll(PDO::FETCH_ASSOC);
       }
       elseif ($fetchWay == "fetchObject") {

       return $statement->fetchAll(PDO::FETCH_OBJ);
       }
   }

    /**
     * @param $sql string
     * @param $fetchWay string
     * @param $params array
     *
     * @return mixed
     */
   public function fetchOne(
       $sql,
       $fetchWay,
       $params = []
   ) {
       $statement = $this->prepareAndExecute(
           $sql,
           $params
       );
       if ($fetchWay == "fetchAssoc") {

           return $statement->fetch(PDO::FETCH_ASSOC);
       }
       elseif ($fetchWay == "fetchObject") {

           return $statement->fetch(PDO::FETCH_OBJ);
       }
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