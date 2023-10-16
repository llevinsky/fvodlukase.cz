<?php
require '../vendor/autoload.php';
require '../model/MailManager.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli('localhost', 'root', '', 'fvodlukase');
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM uzivatel WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            header('Location: ../profile.php');
            exit();
        }
    }

    $_SESSION['errors']['login'] = 'Nesprávné uživatelské jméno nebo heslo!';
}

header('Location: ../login.php');
exit();
