<?php
const TITLE = 'FVodLukáše - Profil';
?>
<?php
require 'components/header.php';
require 'model/entity/User.php';
require 'model/MailManager.php';
require 'vendor/autoload.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user = new User();
$user->getById($_SESSION['user_id']);

// Handle sign off
if (isset($_GET['sign_off'])) {
    session_destroy();
    unset($_SESSION['user_id']); // Unset the specific session variable

    // Delete the remembered_user cookie if it exists
    if (isset($_COOKIE['remembered_user'])) {
        setcookie('remembered_user', '', time() - 3600, '/');
    }

    header('Location: login.php');
    exit();
}

?>
<div class="container-sm">
    <main>
        <h2>Vítejte na Vašem profilu</h2>
        <p>Dobrý den <span class="strong"><?= $user->firstname . " " . $user->lastname ?><span> !</p>
        <p>Byl/a jste zaregistrován/a <?= $user->created_at->format('d.m.Y') ?> v <?= $user->created_at->format('H:i') ?> !</p>
        <p>Poslední změna na Vašem profilu proběhla <?= $user->updated_at->format('d.m.Y') ?> v <?= $user->updated_at->format('H:i') ?> !</p>

        <form action="profile.php" method="GET">
            <button type="submit" name="sign_off">Odhlásit se</button>
        </form>
    </main>
</div>