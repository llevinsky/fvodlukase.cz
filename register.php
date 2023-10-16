<?php
const TITLE = 'FVodLukáše - Registrace';
?>
<?php require 'components/header.php'; ?>
<?php
session_start();
// Pre-filled form data
$formData = isset($_SESSION['formData']) ? $_SESSION['formData'] : array();
$username = isset($formData['username']) ? $formData['username'] : '';
$firstName = isset($formData['firstname']) ? $formData['firstname'] : '';
$lastName = isset($formData['lastname']) ? $formData['lastname'] : '';
$email = isset($formData['email']) ? $formData['email'] : '';
unset($_SESSION['formData']);
?>
<link rel="stylesheet" href="components/css/register.css">

<div class="container-sm">
    <form id="form" action="process/processRegister.php" method="post">
        <h2>Registrace</h2>
        <div class="input-control">
            <label for="username">Uživatelské jméno*</label>
            <input id="username" name="username" type="text" minlength="8" required value="<?php echo $username; ?>">
            <div class="error"></div>
        </div>
        <div class="input-control">
            <label for="firstname">Jméno*</label>
            <input id="firstname" name="firstname" type="text" minlength="2" required value="<?php echo $firstName; ?>">
            <div class="error"></div>
        </div>
        <div class="input-control">
            <label for="lastname">Příjmení*</label>
            <input id="lastname" name="lastname" type="text" minlength="2" required value="<?php echo $lastName; ?>">
            <div class="error"></div>
        </div>
        <div class="input-control">
            <label for="email">Email*</label>
            <input id="email" name="email" type="text" required value="<?php echo $email; ?>">
            <div class="error"></div>
        </div>
        <div class="input-control">
            <label for="password">Heslo*</label>
            <input id="password" name="password" type="password" required>
            <div class="error"></div>
        </div>
        <div class="input-control">
            <label for="password2">Potvrzení hesla*</label>
            <input id="password2" name="password2" type="password" required>
            <div class="error"></div>
        </div>
        <?php
        if (isset($_SESSION['errors']) && is_array($_SESSION['errors'])) {
            $errors = $_SESSION['errors'];
            foreach ($errors as $error) {
                echo '<div style="color:red; padding-bottom:6px">' . $error . '</div>';
            }
            unset($_SESSION['errors']);
        }
        ?>
        <button name="register" type="submit">Zaregistrovat se</button>
        <p>Už máte vytvořený účet? <a href="login.php">Přihlaste se</a></p>
    </form>
</div>

<script src="components/js/registerFormValidation.js"></script>