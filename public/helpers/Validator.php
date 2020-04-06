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
                    if (!$this->$method($param, $additionalParam[1] ?? null)) {
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
     * @param mixed $variable
     * @param mixed $number
     *
     * @return bool
     */
    public function equalTo($variable, $number)
    {
        return $variable == $number ? true : false;
    }

    /**
     * @param mixed $variable
     *
     * @return bool
     */
    public function roundToSelf ($variable)
    {
        return round($variable) == $variable ? true : false;
    }

    /**
     * @param string $variable
     *
     * @return bool
     */
    public function isAlphabetic ($variable)
    {
        return ctype_alpha($variable) ? true : false;
    }
}