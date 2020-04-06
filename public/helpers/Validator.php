<?php

namespace helpers;

use exception\BadRequestException;
use model\DAO\AddressDAO;

class Validator
{
    const MIN_LENGTH_OF_PASSWORD = 8;
    /**
     * @var Validator
     */
    private static $instance;

    /**
     * @return Validator
     */
    public static function getInstance()
    {
        if (Validator::$instance == null) {
            Validator::$instance = new Validator;
        }

        return Validator::$instance;
    }

    /**
     * @param array $paramsAndRules
     *
     * @return bool
     * @throws BadRequestException
     */
    public function validate($paramsAndRules)
    {
        foreach ($paramsAndRules as $param => $rules) {
            $seperatedRules = explode('|', "$rules");
            foreach ($seperatedRules as $rule) {
                $additionalParam = explode(":", $rule);
                $method = $additionalParam[0];
                if (method_exists(new Validator(), $method)) {
                    if (isset($additionalParam[1])) {
                        if (!$this->$method($param, $additionalParam[1])) {

                            return false;
                        }
                    } else {
                        if (!$this->$method($param, $additionalParam = null)) {

                            return false;
                        }
                    }
                } else {
                    throw new BadRequestException();
                }
            }
        }

        return true;
    }

    /**
     * @param mixed $variable
     *
     * @return bool
     */
    public function isVariableSet($variable)
    {
        return isset($variable) ? true : false;
    }

    /**
     * @param mixed $variable
     *
     * @return bool
     */
    public function isNumeric($variable)
    {
        return is_numeric($variable) ? true : false;
    }

    /**
     * @param mixed $variable
     *
     * @return bool
     */
    public function isEmpty($variable)
    {
        return !empty($variable) ? true : false;
    }

    /**
     * @param mixed $variable
     * @param int $number
     *
     * @return bool
     */
    public function biggerThan($variable, $number)
    {
        return $variable > (int)$number ? true : false;
    }

    /**
     * @param mixed $variable
     * @param int $number
     *
     * @return bool
     */
    public function lessThan($variable, $number)
    {
        return $variable < (int)$number ? true : false;
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function validateEmail($email)
    {
        return !preg_match('#[a-zA-Z0-9-_.+]+@[a-zA-Z0-9-]+.[a-zA-Z]+#', $email) ? true : false;
    }

    /**
     * @param string $password
     *
     * @return bool
     */
    public function validatePassword($password)
    {

        if (strlen($password) < self::MIN_LENGTH_OF_PASSWORD) {

            return false;
        } elseif (!preg_match('#[0-9]+#', $password)) {

            return false;
        } elseif (!preg_match('#[A-Z]+#', $password) && !preg_match('#[a-z]+#', $password)) {

            return false;
        }

        return true;
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
        if (!in_array($cityId, $addresses)) {

            return false;
        }

        return true;
    }

    /**
     * @param float $price
     *
     * @return bool
     */
    public function validateProductPrice($price)
    {
        return !preg_match('/^[0-9]+(\.[0-9]{1,2})?$/', $price) ? true : false;
    }
}