<?php

namespace App\Model;

use PDO;

class UserModel
{
    private $conn;
    public $username;
    public $email;
    public $password;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function create()
    {
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        return $stmt->execute();
    }
}
