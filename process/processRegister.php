<?php
session_start();

require '../model/entity/User.php';
require '../model/MailManager.php';
require '../vendor/autoload.php';

$log = new UserLog();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $firstName = trim($_POST['firstname']);
    $lastName = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $password2 = trim($_POST['password2']);

    // Validation regex patterns
    $regexMail = '/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
    $regexName = '/^[A-ZÁČĎÉĚÍŇÓŘŠŤÚŮÝŽáčďéěíňóřšťúůýž][a-záčďéěíňóřšťúůýž]+$/i';

    if (empty($username) || empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($password2)) {
        $log->addError('Nebyla vyplněna všechna pole', true);
    } else {
        if (strlen($username) < 8) {
            $log->addError('Uživatelské jméno musí mít minimálně 8 znaků');
        }

        if (strlen($firstName) < 2) {
            $log->addError('Jméno musí mít minimálně 2 znaky');
        } else if (!preg_match($regexName, $firstName)) {
            $log->addError('Jméno musí začínat velkým písmenem a ostatní písmena musí být malá');
        }

        if (strlen($lastName) < 2) {
            $log->addError('Příjmení musí mít minimálně 2 znaky');
        } else if (!preg_match($regexName, $lastName)) {
            $log->addError('Příjmení musí začínat velkým písmenem a ostatní písmena musí být malá');
        }

        if (!preg_match($regexMail, $email)) {
            $log->addError('Zadejte prosím správnou emailovou adresu');
        }

        if (strlen($password) < 8) {
            $log->addError('Heslo musí mít minimálně 8 znaků');
        }

        if ($password2 !== $password) {
            $log->addError('Hesla se neshodují');
        }

        if ($log->getCountErrors() > 0) {
            $_SESSION['formData'] = [
                'username' => $username,
                'firstname' => $firstName,
                'lastname' => $lastName,
                'email' => $email,
            ];
            $_SESSION['errors'] = $log->getErrors();
            header('Location: ../register.php');
            exit();
        }

        // Check if username or email already exist in the database
        $user = new User();
        if ($user->existsByUsername($username)) {
            $log->addError('Uživatelské jméno již existuje');
        }
        if ($user->existsByEmail($email)) {
            $log->addError('Email již existuje');
        }

        if ($log->getCountErrors() > 0) {
            $_SESSION['formData'] = [
                'username' => $username,
                'firstname' => $firstName,
                'lastname' => $lastName,
                'email' => $email,
            ];
            $_SESSION['errors'] = $log->getErrors();
            header('Location: ../register.php');
            exit();
        }

        // Generate verification code and add the user to the database
        $user->firstname = $firstName;
        $user->lastname = $lastName;
        $user->username = $username;
        $user->password = $password;
        $user->email = $email;

        if ($user->add()) {
            // Redirect to a success page or display a success message
            header('Location: ../login.php');
            exit();
        } else {
            $log->addError('Registrace selhala. Zkuste to prosím znovu později.', true);
        }
    }
} else {
    $log->addError('Nebyla zadána některá z povinných hodnot', true);
}

$_SESSION['errors'] = $log->getErrors();
header('Location: ../register.php');
exit();
