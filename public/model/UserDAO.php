<?php

namespace model;

class UserDAO extends AbstractDAO
{
    /**
     * @param string $email
     *
     * @return array
     */
    public function getUserByEmail($email)
    {
        $params = [];
        $params["email"] = $email;
        $sql = '
            SELECT
                * 
            FROM 
                users 
            WHERE 
                email = :email
                ;';

        return $this->fetchOneObject(
            $sql,
            $params
        );
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function getUserById($id)
    {
        $params = [];
        $params["id"] = $id;
        $sql = '
            SELECT
                *
            FROM 
                users 
            WHERE
                id = :id
                ;';

        return $this->fetchOneObject(
            $sql,
            $params
        );
    }

    /**
     * @param User $user
     */
    public function add(User $user)
    {
        $params = [];
        $params["email"] = $user->getEmail();
        $params["password"] = $user->getPassword();
        $params["firstName"] = $user->getFirstName();
        $params["lastName"] = $user->getLastName();
        $params["age"] = $user->getAge();
        $params["phoneNumber"] = $user->getPhoneNumber();
        $params["role"] = $user->getRole();
        $params["subscription"] = $user->getSubscription();
        $sql = '
            INSERT INTO
                users
                (email,
                 password,
                 first_name,
                 last_name,
                 age,
                 phone_number,
                 role,
                 subscription,
                 date_created)
            VALUES 
                (:email,
                 :password,
                 :firstName,
                 :lastName,
                 :age,
                 :phoneNumber,
                 :role,
                 :subscription,
                 now())
                 ;';
        $this->prepareAndExecute(
            $sql,
            $params
        );
        $user->setId($this->lastInsertId());
    }

    /**
     * @param User $user
     */
    public function update(User $user)
    {
        $params = [];
        $params["email"] = $user->getEmail();
        $params["password"] = $user->getPassword();
        $params["firstName"] = $user->getFirstName();
        $params["lastName"] = $user->getLastName();
        $params["age"] = $user->getAge();
        $params["phoneNumber"] = $user->getPhoneNumber();
        $params["subscription"] = $user->getSubscription();
        $params["id"] = $user->getId();
        $sql = '
            UPDATE 
                users 
            SET 
                email = :email,
                password = :password,
                first_name = :firstName,
                last_name = :lastName,
                age = :age,
                phone_number = :phoneNumber,
                subscription = :subscription
            WHERE 
                id = :id
                ;';
        $this->prepareAndExecute(
            $sql,
            $params
        );
    }

    /**
     * @param string $email
     * @param string $newPassword
     *
     * @return bool
     */
    public function checkEmailExist(
        $email,
        $newPassword
    ) {
        $params = [];
        $params["newPassword"] = $newPassword;
        $params["email"] = $email;
        $sql = '
            UPDATE
                users 
            SET 
                password = :newPassword
            WHERE 
                email = :email
                ;';
        if ($this->prepareAndExecute($sql, $params)) {
            return true;
        } else {
            return false;
        }
    }
}