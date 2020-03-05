<?php
namespace model;
use PDO;

include_once "config.php";
class DBManager{
    private $pdo;
    private static $instance;

    private function __construct()
    {
        $options=array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
        $this->pdo = new PDO('mysql:host='.DB_HOST. ":" . DB_PORT . ';dbname='.DB_NAME , DB_USER, DB_PASS, $options);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    public static function getInstance(){
        if (self::$instance == null){
            self::$instance = new DBManager();
        }
        return self::$instance;
    }
    public function getPDO(){
        return $this->pdo;
    }

}