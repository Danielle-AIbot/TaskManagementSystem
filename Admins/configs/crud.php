<?php

require_once 'db.php';

class crud
{
    private $conn;
    private $table = "users";
    
    public function __construct()
    {
        $database =  new Database();
        $this->conn = $database->connection();
    }

    //Create
    public function C($username, $email, $password, $profile_image, $role)
    {
        $query = "INSERT INTO " . $this->table . " (username, email, password, profpic, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$username, $email, $password, $profile_image, $role]);
    }

    //Read
    public function R()
    {
        $result = $this->conn->query("SELECT * FROM users ORDER BY id ASC");
        $users = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $row;
        }
        return $users;
    }

    //Update
    public function U($id, $name, $email, $image, $role)
    {
        $query = "UPDATE " . $this->table . " SET username = ?, email = ?, profpic = ?, role = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$name, $email, $image, $role, $id]);
    }

    public function getUserById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Delete
    public function D($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

}

?>