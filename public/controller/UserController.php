<?php

namespace controller;

use exception\BadRequestException;
use exception\NotAuthorizedException;

use model\AddressDAO;
use model\User;
use model\UserDAO;
use PHPMailer;
use Request;

class UserController extends AbstractController
{
    const MIN_LENGTH = 8;

    public function login()
    {
        $post = $this->request->postParams();
        $msg = '';
        if (isset($post["login"])) {
            if (empty($post["email"]) || empty($post["password"])) {
                $msg = 'All fields are required!';
            } elseif ($this->validateEmail($post["email"])) {
                $msg = "Invalid email format!";

            } elseif ($this->validatePassword($post["password"])) {
                $msg = "Your Password Must Contain At Least 8 Characters, At Least 1 Number And At Least 1  Letter!";
            }
            if ($msg == "") {

                $userDAO = new UserDAO();
                $user = $userDAO->getUserByEmail($post["email"]);

                if ($user) {
                    if (password_verify($post["password"], $user->password)) {
                        $_SESSION["logged_user_id"] = $user->id;
                        $_SESSION["logged_user_role"] = $user->role;
                        $_SESSION["logged_user_first_name"] = $user->first_name;
                        $_SESSION["logged_user_last_name"] = $user->last_name;
                    } else {
                        include_once "view/login.php";
                        throw new NotAuthorizedException('Invalid username or password!');

                    }
                }
            }

            if ($msg == "") {
                header("Location: /home");
            } else {
                include_once "view/login.php";
                throw new BadRequestException ("$msg");
            }
        }
    }

    public function register()
    {
        $post = $this->request->postParams();
        if (isset($post["register"])) {
            $msg = "";
            if (empty($post["email"]) || empty($post["password"]) || empty($post["confirmPassword"])
                || empty($post["first_name"]) || empty($post["last_name"])
                || empty($post["phone_number"]) || empty($post["age"])) {
                $msg = "All fields are required!";
            } elseif ($this->validateEmail($post["email"])) {
                $msg = "Invalid email format!";
            } elseif ($this->validatePassword($post["password"])) {
                $msg = "Your Password Must Contain At Least 8 Characters, At Least 1 Number And At Least 1  Letter!";
            } elseif ($this->validatePassword($post["confirmPassword"])) {
                $msg = "Your Password Must Contain At Least 8 Characters, At Least 1 Number And At Least 1  Letter!";
            } elseif ($this->nameValidation($post["first_name"])) {
                $msg = "Invalid name format!";
            } elseif ($this->nameValidation($post["last_name"])) {
                $msg = "Invalid name format!";
            } elseif ($this->phoneNumberValidation($post["phone_number"])) {
                $msg = "Invalid Number format!";
            } elseif ($this->ageValidation($post["age"])) {
                $msg = "Invalid age format!";
            } elseif ($post["password"] !== $post["confirmPassword"]) {
                $msg = "Passwords are not the same!";
            }

            if ($msg == "") {
                $userDAO = new UserDAO();
                $user = $userDAO->getUserByEmail($post["email"]);

                if ($user) {
                    $msg = "This email already exist!";
                }
            }


            $subscription = "no";
            if (isset($post["subscription"]) && $post["subscription"] == "on") {
                $subscription = "yes";
            }
            if ($msg == "") {
                $role = "user";
                $password = password_hash($post["password"], PASSWORD_BCRYPT);
                $first_name = ucfirst($post["first_name"]);
                $last_name = ucfirst($post["last_name"]);
                $newUser = new User($post["email"], $password, $first_name, $last_name, $post["age"], $post["phone_number"], $role, $subscription);

                $userDAO = new UserDAO();
                $userDAO->add($newUser);

                $_SESSION["logged_user_id"] = $newUser->getId();
                $_SESSION["logged_user_role"] = $newUser->getRole();
                $_SESSION["logged_user_first_name"] = $newUser->getFirstName();
                $_SESSION["logged_user_last_name"] = $newUser->getLastName();
                header("Location: /home");
            } else {
                throw new BadRequestException("$msg");
            }
        }
    }

    public function edit()
    {
        $post = $this->request->postParams();
        if (isset($post["edit"])) {
            $msg = '';

            if (empty($post["email"]) || empty($post["accountPassword"])
                || empty($post["first_name"]) || empty($post["last_name"])
                || empty($post["phone_number"]) || empty($post["age"])) {
                $msg = "All fields are required!";
            } elseif ($this->validateEmail($post["email"])) {
                $msg = "Invalid email format!";
            } elseif ($this->validatePassword($post["accountPassword"])) {
                $msg = "Your Password Must Contain At Least 8 Characters, At Least 1 Number And At Least 1  Letter!";
            } elseif ($this->nameValidation($post["first_name"])) {
                $msg = "Invalid name format!";
            } elseif ($this->nameValidation($post["last_name"])) {
                $msg = "Invalid name format!";
            } elseif ($this->phoneNumberValidation($post["phone_number"])) {
                $msg = "Invalid Number format!";
            } elseif ($this->ageValidation($post["age"])) {
                $msg = "Invalid age format!";
            }

            if ($msg == "") {

                $userDAO = new UserDAO();
                $user = $userDAO->getUserById($_SESSION["logged_user_id"]);
                if (password_verify($post["accountPassword"], $user->password) == false) {

                    throw new NotAuthorizedException("Incorrect account password!");
                }

                if (empty($post["newPassword"])) {
                    $password = $user->password;
                } else {
                    if ($this->validatePassword($post["newPassword"])) {
                        $msg = "Your Password Must Contain At Least 8 Characters, At Least 1 Number And At Least 1  Letter!";
                        throw new BadRequestException ("$msg");
                    } else {
                        $password = password_hash($post["newPassword"], PASSWORD_BCRYPT);
                    }
                }
            }

            $subscription = null;
            if (isset($post["subscription"]) && $post["subscription"] == "on") {
                $subscription = "yes";
            } elseif (isset($post["subs"])) {
                if ($post["subs"] == "yes") {
                    $subscription = "yes";
                } elseif ($post["subs"] == "no") {
                    $subscription = "no";
                }
            }
            if ($msg == "") {
                $role = "user";
                $first_name = ucfirst($post["first_name"]);
                $last_name = ucfirst($post["last_name"]);
                $user = new User($post["email"], $password, $first_name, $last_name, $post["age"], $post["phone_number"], $role, $subscription);
                $user->setId($_SESSION["logged_user_id"]);

                $userDAO = new UserDAO();
                $userDAO->update($user);
                $msg = "success";
            } else {
                throw new BadRequestException("$msg");
            }
            include_once "view/editProfile.php";
        }
    }

    public function logout()
    {
        if (isset($_SESSION["logged_user_id"])) {
            unset($_SESSION);
            session_destroy();

            header("Location: /home");
        }
    }

    public function loginPage()
    {
        include_once "view/login.php";
    }

    public function registerPage()
    {
        include_once "view/register.php";
    }

    public function account()
    {
        $validateSession = new UserController();
        $validateSession->validateForLoggedUser();
        $userDAO = new UserDAO();
        $user = $userDAO->getUserByid($_SESSION["logged_user_id"]);
        $addressDAO = new AddressDAO();
        $addresses = $addressDAO->getAll($_SESSION["logged_user_id"]);
        include_once "view/account.php";

    }

    public function editPage()
    {
        $this->validateForLoggedUser();
        $userDAO = new UserDAO();
        $user = $userDAO->getUserByid($_SESSION["logged_user_id"]);
        include_once "view/editProfile.php";
    }

    public function getUserById()
    {

        $userDAO = new UserDAO();
        $user = $userDAO->getUserByid($_SESSION["logged_user_id"]);
        unset($user->password);
        return $user;
    }

    public function validateEmail($email)
    {
        $err = false;
        if (!preg_match("#[a-zA-Z0-9-_.+]+@[a-zA-Z0-9-]+.[a-zA-Z]+#", $email)) {
            $err = true;
        }
        return $err;
    }

    public function validatePassword($password)
    {
        $err = false;
        if (strlen($password) < self::MIN_LENGTH) {
            $err = true;
        } elseif (!preg_match("#[0-9]+#", $password)) {
            $err = true;
        } elseif (!preg_match("#[A-Z]+#", $password) && !preg_match("#[a-z]+#", $password)) {
            $err = true;
        }
        return $err;;
    }

    public function nameValidation($name)
    {
        $err = false;
        if (!ctype_alpha($name) || strlen($name) < 2) {
            $err = true;
        }
        return $err;
    }

    public function phoneNumberValidation($phone_number)
    {
        $err = false;
        if (!preg_match('/^[8][0-9]{8}$/', $phone_number) || !is_numeric($phone_number) || $phone_number != round($phone_number)) {
            $err = true;
        }
        return $err;
    }

    public function ageValidation($age)
    {
        $err = false;
        if (!is_numeric($age) || $age < 18 || $age > 100 || $age != round($age)) {
            $err = true;
        }
        return $err;
    }

    public static function validateForLoggedUser()
    {
        if (!isset($_SESSION["logged_user_id"])) {
            header("Location:/loginPage");
        }
    }

    public static function validateForAdmin()
    {
        if (!isset($_SESSION["logged_user_id"]) || $_SESSION["logged_user_role"] != "admin") {
            header("Location: /home");
        }
    }

    public function forgottenPassword()
    {
        include_once "view/forgottenPassword.php";
    }

    public function sendNewPassword()
    {
        $post = $this->request->postParams();
        if (isset($post["forgotPassword"])) {
            if (isset($post["email"])) {
                $emailCheck = new UserDAO();
                $newPassword = substr(md5(uniqid(mt_rand(), true)), 0, 8);
                if ($emailCheck->checkEmailExist($post["email"], password_hash($newPassword, PASSWORD_BCRYPT))) {
                    $email = new UserController();
                    $email->sendEmailPassword($post["email"], $newPassword);
                    include_once "view/login.php";
                }
            }
        }
    }

    public function sendEmailPassword($email, $newPassword)
    {
        require_once "controller/credentials.php";
        require_once "PHPMailer-5.2-stable/PHPMailerAutoload.php";
        $mail = new PHPMailer;
//$mail->SMTPDebug = 3;                               // Enable verbose debug output
        $mail->isSMTP();
        $mail->SMTPDebug = 0;// Set mailer to use SMTP
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Host = 'smtp.sendgrid.net';  // Specify main and backup SMTP servers
        $mail->Username = EMAIL_USERNAME;                 // SMTP username
        $mail->Password = EMAIL_PASSWORD;                           // SMTP password
        $mail->SMTPSecure = 'tsl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->setFrom('emag9648@gmail.com');
        $mail->addAddress($email);     // Add a recipient
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Forgotten Password!!!';
        $mail->Body = "$newPassword is your new password , You can change it in your profile ! Login " . "<a href='http://localhost:8888/It-talents/loginPage'>here</a>";
        $mail->AltBody = '';

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            $msg = 'Message has been sent';
        }
    }
}

