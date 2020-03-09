<?php

namespace model;


class UserDAO{
    public function getUserByEmail($email){

        $pdo = DBManager::getInstance()->getPDO();
        $sql="SELECT * FROM users WHERE email=?;";
        $stmt=$pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(\PDO::FETCH_OBJ);

    }

    public function getUserById($id){

        $pdo = DBManager::getInstance()->getPDO();
        $sql="SELECT * FROM users WHERE id=?;";
        $stmt=$pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_OBJ);

    }

    public function add(User $user){
        $pdo = DBManager::getInstance()->getPDO();
        $params = [];
        $params[] = $user->getEmail();
        $params[] = $user->getPassword();
        $params[] = $user->getFirstName();
        $params[] = $user->getLastName();
        $params[] = $user->getAge();
        $params[] = $user->getPhoneNumber();
        $params[] = $user->getRole();
        $params[]=$user->getSubscription();
        $sql = "INSERT INTO users (email, password, first_name,last_name,age,phone_number,role,subscription,date_created) VALUES (?, ?, ?,?,?,?,?,?,now());";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $user->setId($pdo->lastInsertId());

    }



    public function update(User $user){
        $pdo = DBManager::getInstance()->getPDO();
        $params = [];
        $params[] = $user->getEmail();
        $params[] = $user->getPassword();
        $params[] = $user->getFirstName();
        $params[] = $user->getLastName();
        $params[] = $user->getAge();
        $params[] = $user->getPhoneNumber();
        $params[]=$user->getSubscription();
        $params[] = $user->getId();

        $sql = "UPDATE users SET email=?, password=?, first_name=?,last_name=?,age=?,phone_number=?,subscription=? WHERE id=? ;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);


    }

    public function checkEmailExist($email , $newPassword){
        $pdo = DBManager::getInstance()->getPDO();
        $params = [];
        $params[] = $newPassword;
        $params[] = $email;
        $sql="UPDATE users SET password = ?WHERE email = ?;";
        $stmt=$pdo->prepare($sql);
        if ($stmt->execute($params)){
            return true;
        }
        else{
            return false;
            }



    }

}