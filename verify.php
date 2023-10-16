<?php
session_start();
require 'model/entity/User.php';
require 'model/MailManager.php';
require 'vendor/autoload.php';

if (!isset($_GET['code'])) {
    header('Location: register.php');
    die();
}

$user = new User();
if ($user->verifyByCode($_GET['code'])) {
    // Update the code field to null in the database
    $conn = new mysqli('localhost', 'root', '', 'fvodlukase');
    $stmt = $conn->prepare("UPDATE uzivatel SET code = NULL WHERE code = ?");
    $stmt->bind_param("s", $_GET['code']);
    $stmt->execute();

    // Load the user's details again
    $user->getById($user->user_id);

    $_SESSION['user_id'] = $user->user_id; // Set the user_id in the session

    header('Location: profile.php'); // Redirect to profile.php after successful verification
    exit();
} else {
    $_SESSION['errors']['login'] = 'Please complete the verification process before logging in.';
    header('Location: login.php');
    exit();
}
