<?php
namespace exception;
class BadRequestException extends BaseException {

    function getStatusCode()
    {
        return 400;
    }
}