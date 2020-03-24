<?php

namespace controller;

use exception\BadRequestException;
use exception\NotAuthorizedException;
use model\Address;
use model\DAO\AddressDAO;
use helpers\Request;

class AddressController extends AbstractController
{
    /**
     * @throws BadRequestException
     */
    public function add()
    {
        UserController::validateForLoggedUser();
        $postParams = $this->request->postParams();
        $err = false;
        $msg = '';
        if (isset($postParams['add'])) {
            if (empty($postParams['city']) || empty($postParams['street'])) {
                $msg = 'All fields are required!';
            } elseif (!$this->validateCity($postParams['city'])) {
                $msg = 'Invalid city!';
            }
            if ($msg == '') {
                $address = new Address(
                    $this->session->getSessionParam('logged_user_id'),
                    $postParams['city'],
                    $postParams['street']
                );
                $addressDAO = new AddressDAO();
                $addressDAO->add($address);
                header('Location: /myAccount');
            } else {
                include_once 'view/newAddress.php';

                throw new BadRequestException ($msg);
            }
        }
    }

    /**
     * @throws BadRequestException
     * @throws NotAuthorizedException
     */
    public function edit()
    {
        UserController::validateForLoggedUser();
        $postParams = $this->request->postParams();
        $err = false;
        $msg = '';
        if (isset($postParams['save'])) {
            if (empty($postParams['city']) || empty($postParams['street'])) {
                $msg = 'All fields are required!';
            } elseif (!$this->validateCity($postParams['city'])) {
                $msg = 'Invalid city!';
            }
            if ($msg == '') {
                $address = new Address(
                    $this->session->getSessionParam('logged_user_id'),
                    $postParams['city'],
                    $postParams['street']
                );
                $address->setId($postParams['address_id']);
                $addressDAO = new AddressDAO();
                $addressDetails = $addressDAO->getById($postParams['address_id']);
                if ($addressDetails->user_id === $this->session->getSessionParam('logged_user_id')) {
                    $addressDAO->edit($address);
                } else {
                    throw new NotAuthorizedException('Not Authorized for this operation!');
                }
                header('Location: /myAccount');
            } else {
                throw new BadRequestException($msg);
            }
        }
    }

    /**
     * @throws NotAuthorizedException
     */
    public function delete()
    {
        UserController::validateForLoggedUser();
        $postParams = $this->request->postParams();
        if (isset($postParams['deleteAddress'])) {
            $addressDAO = new AddressDAO();
            $addressDetails = $addressDAO->getById($postParams['address_id']);
            if ($addressDetails->user_id == $this->session->getSessionParam('logged_user_id')) {
                $addressDAO->delete($postParams['address_id']);
            } else {
                throw new NotAuthorizedException('Not Authorized for this operation!');
            }
            header('Location: /myAccount');
        }
    }

    /**
     * @param int $cityId
     *
     * @return bool
     */
    public function validateCity($cityId)
    {
        $err = false;
        $addressDAO = new AddressDAO();
        $addresses = $addressDAO->getCities();
        if (!in_array($cityId, $addresses)) {
            $err = true;
        }

        return $err;
    }

    /**
     *  Including new Address Page
     */
    public function newAddress()
    {
        UserController::validateForLoggedUser();
        include_once 'view/newAddress.php';
    }

    /**
     *  Including edit specified Address Page
     */
    public function editAddress()
    {
        UserController::validateForLoggedUser();
        $postParams = $this->request->postParams();
        $addressDAO = new AddressDAO;
        $address = $addressDAO->getById($postParams['address_id']);
        include_once 'view/editAddress.php';
    }

    /**
     * @return array
     */
    public function getCities()
    {
        $addressDAO = new AddressDAO;

        return $addressDAO->getCities();
    }

    /**
     * @return array
     */
    public function checkUserAddress()
    {
        UserController::validateForLoggedUser();
        $check = new AddressDAO;

        return $check->userAddress($this->session->getSessionParam('logged_user_id'));
    }
}