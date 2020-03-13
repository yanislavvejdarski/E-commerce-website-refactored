<?php
namespace controller;

use exception\BadRequestException;
use exception\NotAuthorizedException;
use model\Address;
use model\AddressDAO;
use Request;


class AddressController{
    public function add(){
        UserController::validateForLoggedUser();
        $request = Request::getInstance();
        $post = $request->postParams();
        $err=false;
        $msg='';
        if(isset($post["add"])){
            if(empty($post["city"]) || empty($post["street"])) {
                $msg = 'All fields are required!';
            }elseif(!$this->validateCity($post["city"])){
                $msg="Invalid city!";
            }
            if($msg==""){

                $address=new Address($_SESSION["logged_user_id"],$post["city"],$post["street"]);
                $addressDAO=new AddressDAO();


                $addressDAO->add($address);

                header("Location: /myAccount");
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
        if(isset($post["save"])){
            if(empty($post["city"]) || empty($post["street"])) {
                $msg = 'All fields are required!';
            }elseif(!$this->validateCity($post["city"])){
                $msg="Invalid city!";
            }
            if($msg==""){
                $address=new Address($_SESSION["logged_user_id"],$post["city"],$post["street"]);
                $address->setId($post["address_id"]);
                $addressDAO=new AddressDAO();
                $addressDetails=$addressDAO->getById($post["address_id"]);
                    if($addressDetails->user_id === $_SESSION["logged_user_id"]){
                        $addressDAO->edit($address);
                    }else{
                        throw new NotAuthorizedException("Not Authorized for this operation!");
                    }

                header("Location: /myAccount");
            }else{

                throw new BadRequestException("$msg");
            }

        }
    }


    public function delete(){
        UserController::validateForLoggedUser();
        if(isset($post["deleteAddress"])){

            $addressDAO=new AddressDAO();
            $addressDetails=$addressDAO->getById($post["address_id"]);
            if($addressDetails->user_id == $_SESSION["logged_user_id"]){
                $addressDAO->delete($post["address_id"]);
            }else{
                throw new NotAuthorizedException("Not Authorized for this operation!");
            }
            header("Location: /myAccount");
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
        $address=$addressDAO->getById($post["address_id"]);
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