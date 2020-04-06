<?php

namespace helpers;

use exception\BadRequestException;
use model\DAO\AddressDAO;

class Validator
{
    /**
     * @param array $paramsAndRules
     *
     * @return bool
     *
     * @throws BadRequestException
     */
    public function validate($paramsAndRules)
    {
        foreach ($paramsAndRules as $param => $rules) {
            $seperatedRules = explode('|', "$rules");
            foreach ($seperatedRules as $rule) {
                $additionalParam = explode(":", $rule);
                $method = $additionalParam[0];
                if (method_exists($this, $method)) {
                    if (isset($additionalParam[1]) && !$this->$method($param, $additionalParam[1])) {

                        return false;
                    } elseif(!$this->$method($param, $additionalParam = null)) {

                       return false;
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
    public function validatePassword($password ,$maximumLength)
    {
        if (
            strlen($password) < $maximumLength
            || !preg_match('#[a-zA-Z0-9]+#', $password)
        ) {

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