<?php

class User
{
    public ?int $user_id = null;
    public string $username;
    public string $firstname;
    public string $lastname;
    public string $password;
    public string $email;
    public ?string $code;
    public DateTime $created_at;
    public DateTime $updated_at;
    private mysqli $conn;

    public function __construct()
    {
        $this->code = md5(uniqid());
        $this->created_at = new DateTime('now');
        $this->updated_at = new DateTime('now');
        $this->conn = new mysqli('localhost', 'root', '', 'fvodlukase');
    }

    public function getById(int $id): void
    {
        $sql = "SELECT * FROM uzivatel WHERE user_id = " . $id;
        $result = $this->conn->query($sql);
        $user = $result->fetch_array();
        $this->loadEntity($user);
    }

    private function loadEntity(array $uzivatel): void
    {
        $this->user_id = $uzivatel["user_id"];
        $this->username = $uzivatel["username"];
        $this->firstname = $uzivatel["firstname"];
        $this->lastname = $uzivatel["lastname"];
        $this->password = $uzivatel["password"];
        $this->email = $uzivatel["email"];
        $this->code = $uzivatel["code"];
        $this->created_at = new DateTime($uzivatel["created_at"]);
        $this->updated_at = new DateTime($uzivatel["updated_at"]);
    }

    public function verifyByCode(string $code): bool
    {
        $sql = "SELECT * FROM uzivatel WHERE code = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $this->loadEntity($user);
            $this->code = null;
            $this->updateCodeInDatabase();
            $this->sendThanks();
            return true;
        }

        return false;
    }

    private function updateCodeInDatabase(): void
    {
        $sql = "UPDATE uzivatel SET code = NULL WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $this->user_id);
        $stmt->execute();
    }

    public function add(): bool
    {
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO uzivatel (username, firstname, lastname, password, email, code, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        $created_at = $this->created_at->format('Y-m-d H:i:s');
        $updated_at = $this->updated_at->format('Y-m-d H:i:s');

        $stmt->bind_param(
            "ssssssss",
            $this->username,
            $this->firstname,
            $this->lastname,
            $hashedPassword,
            $this->email,
            $this->code,
            $created_at,
            $updated_at
        );

        if ($stmt->execute()) {
            $this->sendCode();
            return true;
        } else {
            return false;
        }
    }

    private function sendThanks(): void
    {
        $mail = new MailManager('Děkuji za registraci');
        $mail->addAddress($this->email);
        $mail->setMessage('Dobrý den, ' . $this->firstname . ' ' . $this->lastname . '! Registrace proběhla v pořádku! Můžete se přihlásit <a href="http://localhost/ZaverecnyProjekt/login.php">zde</a>.');
        $mail->send();
    }

    private function sendCode(): void
    {
        $mail = new MailManager('Ověření e-mailu');
        $mail->addAddress($this->email);
        $mail->setMessage('Dobrý den, ' . $this->firstname . ' ' . $this->lastname . '! Klikněte <a href="http://localhost/ZaverecnyProjekt/verify.php?code=' . $this->code . '">zde</a> pro dokončení registrace.');
        $mail->send();
    }

    public function login(string $username, string $password): bool
    {
        $sql = "SELECT * FROM uzivatel WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password']) && $user['code'] === null) {
                $this->loadEntity($user);
                return true;
            }
        }

        return false;
    }

    public function existsByUsername(string $username): bool
    {
        $sql = "SELECT COUNT(*) as count FROM uzivatel WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['count'] > 0;
    }

    public function existsByEmail(string $email): bool
    {
        $sql = "SELECT COUNT(*) as count FROM uzivatel WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['count'] > 0;
    }
}

class UserLog
{
    private array $errors = [];
    private string $logInstance;
    public bool $priority = false;

    public function __construct()
    {
        $this->logInstance = uniqid();
    }

    public function addError(string $message, bool $priority = false)
    {
        $this->errors[] = $message;
        if (!$this->priority && $priority === true) {
            $this->priority = true;
        }
    }

    public function getCountErrors(): int
    {
        return count($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getInstance(): string
    {
        $_SESSION[$this->logInstance] = json_encode($this->errors);
        return $this->logInstance;
    }
}
