<?php
namespace controller;

use exception\BadRequestException;
use exception\NotAuthorizedException;
use model\Address;
use model\AddressDAO;


class AddressController{
    public function add(){
        UserController::validateForLoggedUser();
        $err=false;
        $msg='';
        if(isset($_POST["add"])){
            if(empty($_POST["city"]) || empty($_POST["street"])) {
                $msg = 'All fields are required!';
            }elseif(!$this->validateCity($_POST["city"])){
                $msg="Invalid city!";
            }
            if($msg==""){

                $address=new Address($_SESSION["logged_user_id"],$_POST["city"],$_POST["street"]);
                $addressDAO=new AddressDAO();


                $addressDAO->add($address);

                header("Location: index.php?target=user&action=account");
            }else{

                include_once "view/newAddress.php";
               throw new BadRequestException ($msg);
            }

        }
    }
    public function edit(){
        UserController::validateForLoggedUser();
        $err=false;
        $msg='';
        if(isset($_POST["save"])){
            if(empty($_POST["city"]) || empty($_POST["street"])) {
                $msg = 'All fields are required!';
            }elseif(!$this->validateCity($_POST["city"])){
                $msg="Invalid city!";
            }
            if($msg==""){
                $address=new Address($_SESSION["logged_user_id"],$_POST["city"],$_POST["street"]);
                $address->setId($_POST["address_id"]);
                $addressDAO=new AddressDAO();
                $addressDetails=$addressDAO->getById($_POST["address_id"]);
                    if($addressDetails->user_id === $_SESSION["logged_user_id"]){
                        $addressDAO->edit($address);
                    }else{
                        throw new NotAuthorizedException("Not Authorized for this operation!");
                    }

                header("Location: index.php?target=user&action=account");
            }else{

                throw new BadRequestException("$msg");
            }

        }
    }


    public function delete(){
        UserController::validateForLoggedUser();
        if(isset($_POST["deleteAddress"])){

            $addressDAO=new AddressDAO();
            $addressDetails=$addressDAO->getById($_POST["address_id"]);
            if($addressDetails->user_id == $_SESSION["logged_user_id"]){
                $addressDAO->delete($_POST["address_id"]);
            }else{
                throw new NotAuthorizedException("Not Authorized for this operation!");
            }
            header("Location: index.php?target=user&action=account");
        }
    }

    public function validateCity($cityId){
        $err=false;
        $addressDAO=new AddressDAO();
        $addresses=$addressDAO->getCities();
        if(!in_array($cityId,$addresses)){
            $err=true;
        }
        return $err;
    }

    public function newAddress(){
        UserController::validateForLoggedUser();
        include_once "view/newAddress.php";
    }
    public function editAddress(){
        UserController::validateForLoggedUser();
        $addressDAO=new AddressDAO;
        $address=$addressDAO->getById($_POST["address_id"]);
        include_once "view/editAddress.php";

    }

    public function getCities(){
        $addressDAO=new AddressDAO;
        return $addressDAO->getCities();
    }



    public static function checkUserAddress(){
        UserController::validateForLoggedUser();
        $check = new AddressDAO;
        return $check->userAddress($_SESSION["logged_user_id"]);

    }
}