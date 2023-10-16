<?php

class Contact
{
    public string $firstname;
    public string $lastname;
    public string $email;
    public string $phone;
    public string $msg;
    public array $files = []; // Store the uploaded files as an array

    public function addFile(string $filename): void
    {
        $this->files[] = $filename;
    }
}
