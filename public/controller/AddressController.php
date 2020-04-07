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
        $postParams = $this->request->postParams();
        $paramsAndRules = [
            $postParams['add'] => 'isVariableSet',
            $postParams['city'] => 'isEmpty',
            $postParams['street'] => 'isEmpty'
        ];
        if ($this->validator->validate($paramsAndRules)) {
            $address = new Address(
                $this->session->getSessionParam('loggedUserId'),
                $postParams['city'],
                $postParams['street']
            );
            $addressDAO = new AddressDAO();
            $addressDAO->add($address);
            header('Location: /myAccount');
        } else {
            include_once 'view/newAddress.php';

            throw new BadRequestException ("Invalid Input");
        }
    }

    /**
     * @param int $cityId
     *
     * @return bool
     */
    public function validateCity($cityId)
    {
        $addressDAO = new AddressDAO();
        $addresses = $addressDAO->getCities();

        return in_array($cityId, $addresses) ? true : false;
    }

    /**
     * @throws BadRequestException
     * @throws NotAuthorizedException
     */
    public function edit()
    {
        $postParams = $this->request->postParams();
        $paramsAndRules = [
            $postParams['save'] => 'isVariableSet',
            $postParams['city'] => 'isEmpty',
            $postParams['street'] => 'isEmpty'
        ];

        if ($this->validator->validate($paramsAndRules)) {
            $address = new Address(
                $this->session->getSessionParam('loggedUserId'),
                $postParams['city'],
                $postParams['street']
            );
            $address->setId($postParams['addressId']);
            $addressDAO = new AddressDAO();
            $addressDetails = $addressDAO->getById($postParams['addressId']);
            if ($addressDetails->userId === $this->session->getSessionParam('loggedUserId')) {
                $addressDAO->edit($address);
            } else {
                throw new NotAuthorizedException('Not Authorized for this operation!');
            }
            header('Location: /myAccount');
        } else {
            throw new BadRequestException("Invalid Input");
        }
    }

    /**
     * @throws NotAuthorizedException
     */
    public function delete()
    {
        $postParams = $this->request->postParams();
        $params = [
            $postParams['deleteAddress'] => 'isVariableSet'
        ];
        if ($this->validator->validate($params)) {
            $addressDAO = new AddressDAO();
            $addressDetails = $addressDAO->getById($postParams['addressId']);
            if ($addressDetails->userId == $this->session->getSessionParam('loggedUserId')) {
                $addressDAO->delete($postParams['addressId']);
            } else {
                throw new NotAuthorizedException('Not Authorized for this operation!');
            }
            header('Location: /myAccount');
        }
    }

    /**
     *  Including new Address Page
     */
    public function newAddress()
    {
        include_once 'view/newAddress.php';
    }

    /**
     *  Including edit specified Address Page
     */
    public function editAddress()
    {
        $postParams = $this->request->postParams();
        $addressDAO = new AddressDAO;
        $address = $addressDAO->getById($postParams['addressId']);
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
        $check = new AddressDAO;

        return $check->userAddress($this->session->getSessionParam('loggedUserId'));
    }
}