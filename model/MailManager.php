<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailManager
{
    private PHPMailer $PHPMailer;
    private string $subject = '';
    private string $message = '';

    public function __construct(string $subject)
    {
        $this->subject = $subject;
        $this->PHPMailer = new PHPMailer(true);
        $this->PHPMailer->SMTPAuth = true;
        $this->PHPMailer->isSMTP();
        $this->PHPMailer->Host = 'smtp.seznam.cz';
        $this->PHPMailer->Username = 'fvodlukase@seznam.cz';
        $this->PHPMailer->Password = '22068AdGmd&i^p';
        $this->PHPMailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->PHPMailer->Port = 465;
        $this->PHPMailer->setFrom('fvodlukase@seznam.cz', 'FVodLukáše');
        $this->PHPMailer->isHTML(true);
        $this->PHPMailer->CharSet = 'UTF-8';
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function addAddress(string $address, ?string $name = null): void
    {
        $this->PHPMailer->addAddress($address, $name);
    }

    public function addAttachment(string $filePath, string $filename): void
    {
        $this->PHPMailer->addAttachment($filePath, $filename);
    }

    public function send(): bool
    {
        $this->PHPMailer->Subject = $this->subject;
        $this->PHPMailer->Body = $this->message;

        try {
            $this->PHPMailer->send();
            return true;
        } catch (Exception $e) {
            // Handle the exception or log the error if needed
            // For simplicity, we'll return false here
            return false;
        }
    }
}
