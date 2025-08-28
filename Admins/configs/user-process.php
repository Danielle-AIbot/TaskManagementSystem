<?php

    require_once 'db.php';

    class Users
    {
        private $conn;
        private $table = 'users';

        public function __construct()
        {
            $database = new Database();
            $this->conn = $database->connection();
        }

        public function Register($username, $email, $password, $role, $profpic)
        {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO " . $this->table . " (username, email, password, role, profpic) VALUES (:username, :email, :password, :role, :profpic)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':profpic', $profpic);

            return $stmt->execute();
        }

        public function Login($username, $password, $role)
        {
            $query = "SELECT * FROM " . $this->table . " WHERE username = ? AND role = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$username, $role]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id']; // <-- set session here
                return $user;
            }
            return false;
        }

        public function Logout()
        {
            unset($_SESSION['admin_id']);
            unset($_SESSION['user_id']);
            unset($_SESSION['admin_username']);
            unset($_SESSION['user_username']);
            session_destroy();
            header("Location: ../../index.php");
            exit();
        }
    }

?>