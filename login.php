<?php
session_start();
const TITLE = 'FVodLukáše - Přihlášení';
?>
<?php require 'components/header.php' ?>
<?php

require 'model/entity/User.php';
require 'model/MailManager.php';
require 'vendor/autoload.php';

$log = new UserLog();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: profile.php');
    exit();
}

// Retrieve login error from session
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']); // Remove login error from session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $log->addError('Prosím, vyplňte všechna pole.');

        // Save errors in session
        $_SESSION['errors'] = $log->getErrors();

        header('Location: login.php');
        exit();
    } else {
        $user = new User();

        // Retrieve username, password, and code
        $conn = new mysqli('localhost', 'root', '', 'fvodlukase');
        $stmt = $conn->prepare("SELECT * FROM uzivatel WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                if ($user['code'] === null || $user['code'] === '') {
                    // Store user information in session
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['user_firstname'] = $user['firstname'];
                    $_SESSION['user_lastname'] = $user['lastname'];
                    $_SESSION['user_email'] = $user['email'];

                    // Check if "Zapamatuj si mě" checkbox is checked
                    if (isset($_POST['remember']) && $_POST['remember'] === 'on') {
                        // Create a cookie to remember the user
                        setcookie('remembered_user', $username, time() + (86400 * 30), '/'); // Cookie is set for 30 days
                    } else {
                        // Delete the remembered_user cookie if it exists
                        if (isset($_COOKIE['remembered_user'])) {
                            setcookie('remembered_user', '', time() - 3600, '/');
                        }
                    }

                    header('Location: profile.php');
                    exit();
                } else {
                    // Check if verification has been completed
                    $_SESSION['errors']['login'] = 'Prosím potvrďte verifikační kód z emailu!';
                    header('Location: login.php');
                    exit();
                }
            }
        }

        // Save login error in session
        $_SESSION['errors']['login'] = 'Nesprávné uživatelské jméno nebo heslo!';

        header('Location: login.php');
        exit();
    }
} elseif (isset($_COOKIE['remembered_user'])) {
    // If remembered_user cookie exists, automatically log in the user to the profile
    $username = $_COOKIE['remembered_user'];

    $user = new User();
    $conn = new mysqli('localhost', 'root', '', 'fvodlukase');
    $stmt = $conn->prepare("SELECT * FROM uzivatel WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if ($user['code'] === null || $user['code'] === '') {
            // Store user information in session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_firstname'] = $user['firstname'];
            $_SESSION['user_lastname'] = $user['lastname'];
            $_SESSION['user_email'] = $user['email'];
            header('Location: profile.php');
            exit();
        } else {
            // Check if verification has been completed
            $_SESSION['errors']['login'] = 'Prosím potvrďte verifikační kód z emailu!';
            header('Location: login.php');
            exit();
        }
    }
}

?>

<link rel="stylesheet" href="components/css/register.css">

<div class="container-sm">
    <form id="form" action="login.php" method="post">
        <h2>Přihlášení</h2>
        <div class="input-control">
            <label for="username">Uživatelské jméno*</label>
            <input id="username" name="username" type="text" minlength="8" required>
            <div class="error"></div>
        </div>
        <div class="input-control">
            <label for="password">Heslo*</label>
            <input id="password" name="password" type="password">
            <div class="error"></div>
        </div>
        <div class="remember">
            <input id="remember" name="remember" type="checkbox">
            <p id="rememberP">Zapamatuj si mě</p>
        </div>
        <?php
        if (isset($_SESSION['errors']['login'])) {
            echo '<div class="php-error" style="color:red; padding-bottom:6px; font-size:15px">' . $_SESSION['errors']['login'] . '</div>';
            unset($_SESSION['errors']['login']);
        }
        ?>
        <button type="submit">Přihlásit se</button>
        <p>Ještě nemáte vytvořený účet? <a href="register.php">Zaregistrujte se</a></p>
    </form>
</div>

<script src="components/js/loginFormValidation.js"></script>

<?php require 'components/footer.php'; ?>