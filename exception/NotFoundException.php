<?php
namespace exception;
class NotFoundException extends BaseException {

    function getStatusCode()
    {
        return 404;
    }
}