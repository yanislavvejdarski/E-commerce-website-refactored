<?php

namespace router;

class Authenticate
{
    /**
     *  Check If User is logged in
     */
    public static function authenticateLoggedUser()
    {
        if (!isset($_SESSION["logged_user_id"])) {
            header("Location: /loginPage");
            die();
        }
    }

    /**
     *  Check If Role Is Admin
     */
    public static function authenticateAdmin()
    {
        if ($_SESSION["logged_user_role"] != "admin") {
            header("Location: /home");
            die();
        }
    }
}