<?php

namespace router;

use helpers\Session;

class Authenticator
{
    /**
     *  Check If User is logged in
     */
    public static function authenticateLoggedUser()
    {
        $loggedUserId = Session::getInstance()->getSessionParam("logged_user_id");
        if (!isset($loggedUserId)) {
            header("Location: /loginPage");
            die();
        }
    }

    /**
     *  Check If Role Is Admin
     */
    public static function authenticateAdmin()
    {
        $loggedRole = Session::getInstance()->getSessionParam("logged_user_role");
        if ($loggedRole != "admin") {
            header("Location: /home");
            die();
        }
    }
}