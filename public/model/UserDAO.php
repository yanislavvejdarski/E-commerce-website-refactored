<?php

namespace model;

class UserDAO extends AbstractDAO
{
    /**
     * @param $email string
     *
     * @return array
     */
    public function getUserByEmail($email)
    {
        $params = [];
        $params[] = $email;
        $sql = "SELECT * FROM users 
                WHERE email=?;";

        return $this->fetchOneObject(
            $sql,
            $params
        );
    }

    /**
     * @param $id int
     *
     * @return array
     */
    public function getUserById($id)
    {
        $params = [];
        $params[] = $id;
        $sql = "SELECT * FROM users 
                WHERE id=?;";

        return $this->fetchOneObject(
            $sql,
            $params
        );
    }

    /**
     * @param $user object
     */
    public function add(User $user)
    {
        $params = [];
        $params[] = $user->getEmail();
        $params[] = $user->getPassword();
        $params[] = $user->getFirstName();
        $params[] = $user->getLastName();
        $params[] = $user->getAge();
        $params[] = $user->getPhoneNumber();
        $params[] = $user->getRole();
        $params[] = $user->getSubscription();
        $sql = "INSERT INTO users (email, password, first_name,last_name,age,phone_number,role,subscription,date_created)
                VALUES (?, ?, ?,?,?,?,?,?,now());";
        $this->prepareAndExecute(
            $sql,
            $params
        );
        $user->setId($this->lastInsertId());
    }

    /**
     * @param $user object
     */
    public function update(User $user)
    {
        $params = [];
        $params[] = $user->getEmail();
        $params[] = $user->getPassword();
        $params[] = $user->getFirstName();
        $params[] = $user->getLastName();
        $params[] = $user->getAge();
        $params[] = $user->getPhoneNumber();
        $params[] = $user->getSubscription();
        $params[] = $user->getId();
        $sql = "UPDATE users 
                SET email=?, password=?, first_name=?,last_name=?,age=?,phone_number=?,subscription=?
                WHERE id=? ;";
        $this->prepareAndExecute(
            $sql,
            $params
        );
    }

    /**
     * @param $email string
     * @param $newPassword string
     *
     * @return bool
     */
    public function checkEmailExist(
        $email,
        $newPassword
    ) {
        $params = [];
        $params[] = $newPassword;
        $params[] = $email;
        $sql = "UPDATE users 
                SET password = ?
                WHERE email = ?;";
        if ($this->prepareAndExecute($sql, $params)) {
            return true;
        } else {
            return false;
        }
    }
}