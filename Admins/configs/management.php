<?php
// filepath: c:\wamp64\www\2nd_year\Task_Management_System\configs\admin.php

require_once 'db.php';

class AdminManager
{
    private $conn;
    public $admin;
    public $account_image;
    public $users = [];

    public function __construct($conn, $admin_id)
    {
        $this->conn = $conn;
        $this->loadAdmin($admin_id);
        $this->loadUsers();
    }

    private function loadAdmin($admin_id)
    {
        $query = "SELECT username, role, profpic FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $admin_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $this->admin = $result->fetch_assoc();
        $this->account_image = !empty($this->admin['profpic']) ? $this->admin['profpic'] : 'default.jpg';
        $stmt->close();
    }

    private function loadUsers()
    {
        $query = "SELECT * FROM users"; // <-- Remove WHERE role = 'user'
        $result = $this->conn->query($query);
        if ($result) {
            $this->users = $result->fetch_all(MYSQLI_ASSOC);
        }
    }
    public function getCompletedTasks()
    {
        $sql = "SELECT t.*, u.username AS assigned_to 
            FROM tasks t 
            JOIN users u ON t.user_id = u.id 
            WHERE t.status = 'completed'
            ORDER BY t.due_date DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>