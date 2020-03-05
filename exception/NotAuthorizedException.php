<?php
namespace exception;
class NotAuthorizedException extends BaseException {

    function getStatusCode()
    {
        return 401;
    }
}