<?php

namespace controller;

use exception\BadRequestException;
use exception\NotAuthorizedException;

use helpers\Session;
use model\DAO\AddressDAO;
use model\User;
use model\DAO\UserDAO;
use PHPMailer;
use helpers\Request;
use phpmailerException;

class UserController extends AbstractController
{

    const MIN_LENGTH = 8;

    /**
     * @throws BadRequestException
     * @throws NotAuthorizedException
     */
    public function login()
    {
        $postParams = $this->request->postParams();
        $paramsAndRules = [
            $postParams['login'] => 'isVariableSet',
            $postParams['password'] => 'isEmpty'

        ];
        $msg = '';
        if ($this->validator->validate($paramsAndRules)) {
            if ($this->validateEmail($postParams['email'])) {
                $msg = 'Invalid email format!';
            } elseif ($this->validatePassword($postParams['password'], self::MIN_LENGTH)) {
                $msg = 'Your Password Must Contain At Least 8 Characters, At Least 1 Number And At Least 1  Letter!';
            }
            if ($msg == '') {
                $userDAO = new UserDAO();
                $user = $userDAO->getUserByEmail($postParams['email']);
                if ($user) {
                    if (password_verify($postParams['password'], $user->password)) {
                        $this->session->setSessionParam('loggedUserId', $user->id);
                        $this->session->setSessionParam('loggedUserRole', $user->role);
                        $this->session->setSessionParam('loggedUserFirstName', $user->firstName);
                        $this->session->setSessionParam('loggedUserLastName', $user->lastName);
                    } else {
                        include_once 'view/login.php';
                        throw new NotAuthorizedException('Invalid username or password!');
                    }
                }
            }
            if ($msg == '') {
                header('Location: /home');
            } else {
                include_once 'view/login.php';
                throw new BadRequestException ($msg);
            }
        }
    }

    /**
     * @throws BadRequestException
     */
    public function register()
    {
        $postParams = $this->request->postParams();
        $paramsAndRules = [
            $postParams['register'] => 'isVariableSet',
            $postParams['email'] => 'isEmpty',
            $postParams['password'] => 'isEmpty',
            $postParams['confirmPassword'] => 'isEmpty',
            $postParams['firstName'] => 'isEmpty',
            $postParams['lastName'] => 'isEmpty',
            $postParams['phoneNumber'] => 'isEmpty',
            $postParams['age'] => 'isEmpty'
        ];
        if ($this->validator->validate($paramsAndRules)) {
            $msg = '';
            if ($this->validateEmail($postParams['email'])) {
                $msg = 'Invalid email format!';
            } elseif ($this->validatePassword($postParams['password'], self::MIN_LENGTH)) {
                $msg = 'Your Password Must Contain At Least 8 Characters, At Least 1 Number And At Least 1  Letter!';
            } elseif ($this->validatePassword($postParams['confirmPassword'], self::MIN_LENGTH)) {
                $msg = 'Your Password Must Contain At Least 8 Characters, At Least 1 Number And At Least 1  Letter!';
            } elseif ($this->nameValidation($postParams['firstName'])) {
                $msg = 'Invalid name format!';
            } elseif ($this->nameValidation($postParams['lastName'])) {
                $msg = 'Invalid name format!';
            } elseif ($this->phoneNumberValidation($postParams['phoneNumber'])) {
                $msg = 'Invalid Number format!';
            } elseif ($this->ageValidation($postParams['age'])) {
                $msg = 'Invalid age format!';
            } elseif ($postParams['password'] !== $postParams['confirmPassword']) {
                $msg = 'Passwords are not the same!';
            }
            if ($msg == '') {
                $userDAO = new UserDAO();
                $user = $userDAO->getUserByEmail($postParams['email']);
                if ($user) {
                    $msg = 'This email already exist!';
                }
            }
            $subscription = 'no';
            if (isset($postParams['subscription']) && $postParams['subscription'] == 'on') {
                $subscription = 'yes';
            }
            if ($msg == '') {
                $role = 'user';
                $password = password_hash($postParams['password'], PASSWORD_BCRYPT);
                $firstName = ucfirst($postParams['firstName']);
                $lastName = ucfirst($postParams['lastName']);
                $newUser = new User(
                    $postParams['email'],
                    $password,
                    $firstName,
                    $lastName,
                    $postParams['age'],
                    $postParams['phoneNumber'],
                    $role,
                    $subscription
                );
                $userDAO = new UserDAO();
                $userDAO->add($newUser);
                $this->session->setSessionParam(
                    'loggedUserId',
                    $newUser->getId()
                );
                $this->session->setSessionParam(
                    'loggedUserRole',
                    $newUser->getRole()
                );
                $this->session->setSessionParam(
                    'loggedUserFirstName',
                    $newUser->getFirstName()
                );
                $this->session->setSessionParam(
                    'loggedUserLastName',
                    $newUser->getLastName()
                );
                header('Location: /home');
            } else {
                throw new BadRequestException('$msg');
            }
        }
    }

    /**
     * @throws BadRequestException
     * @throws NotAuthorizedException
     */
    public function edit()
    {
        $postParams = $this->request->postParams();
        $paramsAndRules = [
            $postParams['edit'] => 'isVariableSet',
            $postParams['accountPassword'] => 'isEmpty',
            $postParams['firstName'] => 'isEmpty',
            $postParams['lastName'] => 'isEmpty',
            $postParams['phoneNumber'] => 'isEmpty',
            $postParams['age'] => 'isEmpty'
        ];
        if ($this->validator->validate($paramsAndRules)) {
            $msg = '';
            if ($this->validateEmail($postParams['email'])) {
                $msg = 'Invalid email format!';
            } elseif ($this->validatePassword($postParams['accountPassword'], self::MIN_LENGTH)) {
                $msg = 'Your Password Must Contain At Least 8 Characters, At Least 1 Number And At Least 1  Letter!';
            } elseif ($this->nameValidation($postParams['firstName'])) {
                $msg = 'Invalid name format!';
            } elseif ($this->nameValidation($postParams['lastName'])) {
                $msg = 'Invalid name format!';
            } elseif ($this->phoneNumberValidation($postParams['phoneNumber'])) {
                $msg = 'Invalid Number format!';
            } elseif ($this->ageValidation($postParams['age'])) {
                $msg = 'Invalid age format!';
            }
            if ($msg == '') {
                $userDAO = new UserDAO();
                $user = $userDAO->getUserById($this->session->getSessionParam('loggedUserId'));
                if (password_verify($postParams['accountPassword'], $user->password) == false) {
                    throw new NotAuthorizedException('Incorrect account password!');
                }
                if (empty($postParams['newPassword'])) {
                    $password = $user->password;
                } else {
                    if ($this->validatePassword($postParams['newPassword'], 8)) {
                        $msg = 'Your Password Must Contain At Least 8 Characters, At Least 1 Number And At Least 1  Letter!';
                        throw new BadRequestException ('$msg');
                    } else {
                        $password = password_hash($postParams['newPassword'], PASSWORD_BCRYPT);
                    }
                }
            }
            $subscription = null;
            if (isset($postParams['subscription']) && $postParams['subscription'] == 'on') {
                $subscription = 'yes';
            } elseif (isset($postParams['subs'])) {
                if ($postParams['subs'] == 'yes') {
                    $subscription = 'yes';
                } elseif ($postParams['subs'] == 'no') {
                    $subscription = 'no';
                }
            }
            if ($msg == '') {
                $role = 'user';
                $firstName = ucfirst($postParams['firstName']);
                $lastName = ucfirst($postParams['lastName']);
                $user = new User(
                    $postParams['email'],
                    $password,
                    $firstName,
                    $lastName,
                    $postParams['age'],
                    $postParams['phoneNumber'],
                    $role,
                    $subscription
                );
                $user->setId($this->session->getSessionParam('loggedUserId'));
                $userDAO = new UserDAO();
                $userDAO->update($user);
                $msg = 'success';
            } else {
                throw new BadRequestException('$msg');
            }
            include_once 'view/editProfile.php';
        }
    }

    /**
     * Log Out
     */
    public function logout()
    {
        $sessionParams = $this->session->getSessionParams();
        $paramsAndRules = [
            $sessionParams['loggedUserId'] => 'isVariableSet'
        ];
        if ($this->validator->validate($paramsAndRules)) {
            $this->session->sessionDestroy();
            header('Location: /home');
        }
    }

    /**
     * Include Login Page
     */
    public function loginPage()
    {
        include_once 'view/login.php';
    }

    /**
     * Include Register Page
     */
    public function registerPage()
    {
        include_once 'view/register.php';
    }

    /**
     * Show MyAccount Page
     */
    public function account()
    {
        $sessionParams = $this->session->getSessionParams();
        $userDAO = new UserDAO();
        $user = $userDAO->getUserByid($sessionParams['loggedUserId']);
        $addressDAO = new AddressDAO();
        $addresses = $addressDAO->getAll($sessionParams['loggedUserId']);
        include_once 'view/account.php';
    }

    /**
     * Show Edit MyAccount Page
     */
    public function editPage()
    {
        $userDAO = new UserDAO();
        $user = $userDAO->getUserByid($this->session->getSessionParam('loggedUserId'));
        include_once 'view/editProfile.php';
    }

    /**
     * @return array
     */
    public function getUserById()
    {
        $userDAO = new UserDAO();
        $user = $userDAO->getUserByid($this->session->getSessionParam('loggedUserId'));
        unset($user->password);
        return $user;
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

    public function validatePassword($password, $maximumLength)
    {
        if (
            strlen($password) < $maximumLength
            || !preg_match('#[a-zA-Z0-9]+#', $password)
        ) {

            return true;
        }

        return false;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function nameValidation($name)
    {
        $paramsAndRules = [
            $name => 'isAlphabetic|biggerThan:2'
        ];
        if ($this->validator->validate($paramsAndRules)) {
            return true;
        }
        return false;
    }

    /**
     * @param int $phoneNumber
     *
     * @return bool
     */
    public function phoneNumberValidation($phoneNumber)
    {
        $paramsAndRules = [
            $phoneNumber => 'isNumeric|roundToSelf'
        ];
        if (!$this->validator->validate($paramsAndRules) || !preg_match('/^[8][0-9]{8}$/', $phoneNumber)) {
            return true;
        }
        return true;
    }

    /**
     * @param int $age
     *
     * @return bool
     */
    public function ageValidation($age)
    {
        $paramsAndRules = [
            $age => 'isNumeric|biggerThan:17|lessThan:100|roundToSelf'
        ];
        if (!$this->validator->validate($paramsAndRules)) {
            return true;
        }
        return false;
    }

    /**
     * Include Forgotten Password Page
     */
    public function forgottenPassword()
    {
        include_once 'view/forgottenPassword.php';
    }

    /**
     * Send New Generated Password
     */
    public function sendNewPassword()
    {
        $postParams = $this->request->postParams();
        $paramsAndRules = [
            $postParams['forgotPassword'] => 'isVariableSet',
            $postParams['email'] => 'isVariableSet'
        ];
        if ($this->validator->validate($paramsAndRules)) {
            $emailCheck = new UserDAO();
            $newPassword = substr(md5(uniqid(mt_rand(), true)), 0, 8);
            if ($emailCheck->checkEmailExist(
                $postParams['email'],
                password_hash($newPassword,
                    PASSWORD_BCRYPT))
            ) {
                $email = new UserController();
                $email->sendEmailPassword(
                    $postParams['email'],
                    $newPassword
                );
                include_once 'view/login.php';
            }

        }
    }

    /**
     * @param $email
     * @param $newPassword
     *
     * @throws phpmailerException
     */
    public function sendEmailPassword(
        $email,
        $newPassword
    )
    {
        require_once 'controller/credentials.php';
        require_once 'PHPMailer-5.2-stable/PHPMailerAutoload.php';
        $mail = new PHPMailer;
        //$mail->SMTPDebug = 3;                       // Enable verbose debug output
        $mail->isSMTP();
        $mail->SMTPDebug = 0;                         // Set mailer to use SMTP
        $mail->SMTPAuth = true;                       // Enable SMTP authentication
        $mail->Host = 'smtp.sendgrid.net';            // Specify main and backup SMTP servers
        $mail->Username = EMAIL_USERNAME;             // SMTP username
        $mail->Password = EMAIL_PASSWORD;             // SMTP password
        $mail->SMTPSecure = 'tsl';                    // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                            // TCP port to connect to
        $mail->setFrom('emag9648@gmail.com');
        $mail->addAddress($email);                    // Add a recipient
        $mail->isHTML(true);                   // Set email format to HTML
        $mail->Subject = 'Forgotten Password!!!';
        $mail->Body = "$newPassword is your new password , You can change it in your profile ! Login " . '<a href="http://php.local/loginPage">here</a>';
        $mail->AltBody = '';
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            $msg = 'Message has been sent';
        }
    }
}