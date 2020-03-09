<?php
namespace exception;
use Exception;
abstract class BaseException extends Exception {
    abstract function getStatusCode();
}