<?php

namespace model;
class User
{
    private $id;
    private $email;
    private $password;
    private $firstName;
    private $lastName;
    private $age;
    private $phoneNumber;
    private $role;
    private $subscription;


    public function __construct($email,$password,$firstName,$lastName,$age,$phoneNumber,$role,$subscription){

        $this->email=$email;
        $this->password=$password;
        $this->firstName=$firstName;
        $this->lastName=$lastName;
        $this->age=$age;
        $this->phoneNumber=$phoneNumber;
        $this->role=$role;
        $this->subscription=$subscription;
    }


    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }


    public function getEmail()
    {
        return $this->email;
    }



    public function getPassword()
    {
        return $this->password;
    }


    public function getFirstName()
    {
        return $this->firstName;
    }



    public function getLastName()
    {
        return $this->lastName;
    }



    public function getAge()
    {
        return $this->age;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }


    public function getRole()
    {
        return $this->role;
    }


    public function getSubscription()
    {
        return $this->subscription;
    }

}