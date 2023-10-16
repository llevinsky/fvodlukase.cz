<?php
session_start();
require '../vendor/autoload.php';
require '../model/entity/Contact.php';
require '../model/MailManager.php';

if (!isset($_POST['contact'])) {
    header('Location: ../kontakt.php');
    die();
}

// Validate and sanitize form data
$firstname = isset($_POST['firstname']) ? $_POST['firstname'] : '';
$lastname = isset($_POST['lastname']) ? $_POST['lastname'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
$msg = isset($_POST['msg']) ? $_POST['msg'] : '';

$errors = array(); // Initialize an empty array to store errors

// Validate phone number format
$phonePattern = '/^\+420\s\d{3}\s\d{3}\s\d{3}$/';
if (!preg_match($phonePattern, $phone)) {
    $errors['phone'] = "Neplatné telefonní číslo: " . $phone;
}

// Validate email format
$emailPattern = '/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
if (!preg_match($emailPattern, $email)) {
    $errors['email'] = "Neplatný formát emailu: " . $email;
}

// Check if any errors occurred
if (!empty($errors)) {
    $_SESSION['error_messages'] = $errors;
    header('Location: ../kontakt.php');
    exit();
}

// Create a new Contact instance
$contactform = new Contact();
$contactform->firstname = $firstname;
$contactform->lastname = $lastname;
$contactform->email = $email;
$contactform->phone = $phone;
$contactform->msg = $msg;

// Create a new MailManager instance and pass the $subject variable
$subject = "Nový kontaktní formulář od $firstname $lastname";
$message = "Jméno: $firstname<br>";
$message .= "Příjmení: $lastname<br>";
$message .= "Email: $email<br>";
$message .= "Telefon: $phone<br>";
$message .= "Zpráva: $msg<br>";

$mailManager = new MailManager($subject);

// Set the message for the email
$mailManager->setMessage($message);

// Add the recipient email address
$recipientEmail = 'fvodlukase@seznam.cz'; // Change this to the actual recipient's email address
$mailManager->addAddress($recipientEmail);

// Check if any files were uploaded and attach them as email attachments
if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['file'];
    $attachmentData = file_get_contents($file['tmp_name']);
    $mailManager->addAttachment($attachmentData, $file['name']);
}

// Send the email
if ($mailManager->send()) {
    // Send confirmation email to the submitter
    $confirmationSubject = "Formulář úspěšně zpracován";
    $confirmationMessage = "Děkuji za vyplnění formuláře. Ozvu se Vám co nejdříve.";
    $submitterMailManager = new MailManager($confirmationSubject);
    $submitterMailManager->addAddress($email);
    $submitterMailManager->setMessage($confirmationMessage);
    $submitterMailManager->send();

    // Redirect to the success page
    header('Location: ../kontakt-success.php');
    exit();
} else {
    // Handle the case where email sending failed
    // Display an error message or take appropriate action
    // For simplicity, we'll redirect to the error page
    header('Location: ../kontakt-error.php');
    exit();
}
