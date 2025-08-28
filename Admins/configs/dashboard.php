<?php

require_once 'db.php';

class Dashboard
{
    private $conn;
    public $user;
    public $account_image;
    public $users_count;
    public $tasks_count;
    public $completed_tasks_count;
    public $recent_activities;

    public function __construct($conn, $user_id)
    {
        $this->conn = $conn;
        $this->User($user_id);
        $this->Stats();
        $this->RecentActivities();
    }

    private function User($user_id)
    {
        $query = "SELECT id, username, role, profpic FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);
        $this->user = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->account_image = !empty($this->user['profpic']) ? $this->user['profpic'] : 'default.jpg';
    }

    private function Stats()
    {
        if ($this->user['role'] == 'admin') {
            // Only count users with role 'user' (students)
            $stmt = $this->conn->query("SELECT COUNT(*) AS users_count FROM users WHERE role = 'user'");
            $this->users_count = $stmt->fetch(PDO::FETCH_ASSOC)['users_count'];

            $stmt = $this->conn->query("SELECT COUNT(*) AS tasks_count FROM tasks");
            $this->tasks_count = $stmt->fetch(PDO::FETCH_ASSOC)['tasks_count'];

            $stmt = $this->conn->query("SELECT COUNT(*) AS completed_tasks_count FROM tasks WHERE status = 'completed'");
            $this->completed_tasks_count = $stmt->fetch(PDO::FETCH_ASSOC)['completed_tasks_count'];
        } else {
            $user_id = $this->user['id'];
            $stmt = $this->conn->prepare("SELECT COUNT(*) AS tasks_count FROM tasks WHERE user_id = ?");
            $stmt->execute([$user_id]);
            $this->tasks_count = $stmt->fetch(PDO::FETCH_ASSOC)['tasks_count'];

            $stmt = $this->conn->prepare("SELECT COUNT(*) AS completed_tasks_count FROM tasks WHERE user_id = ? AND status = 'completed'");
            $stmt->execute([$user_id]);
            $this->completed_tasks_count = $stmt->fetch(PDO::FETCH_ASSOC)['completed_tasks_count'];

            $this->users_count = 1;
        }
    }

    private function RecentActivities()
    {
        if ($this->user['role'] == 'admin') {
            $stmt = $this->conn->query("SELECT username, activity, created_at FROM activities ORDER BY created_at DESC LIMIT 5");
            $this->recent_activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $username = $this->user['username'];
            $stmt = $this->conn->prepare("SELECT username, activity, created_at FROM activities WHERE username = ? ORDER BY created_at DESC LIMIT 5");
            $stmt->execute([$username]);
            $this->recent_activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function getPendingTasksCount()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS count FROM tasks WHERE user_id = ? AND status = 'pending'");
        $stmt->execute([$this->user['id']]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public function getCompletedTasksCount()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS count FROM tasks WHERE user_id = ? AND status = 'completed'");
        $stmt->execute([$this->user['id']]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public function getTasksDueSoon($days = 7)
    {
        $sql = "SELECT t.*, a.username AS assigned_by_name, t.assigned_by
                FROM tasks t
                LEFT JOIN users a ON t.assigned_by = a.id
                WHERE t.user_id = ? AND t.due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)
                ORDER BY t.due_date ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$this->user['id'], $days]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUsernames()
    {
        $stmt = $this->conn->query("SELECT username FROM users WHERE role = 'user'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getRecentActivities()
    {
        $sql = "SELECT a.*, u.username 
            FROM activities a
            JOIN users u ON a.user_id = u.id
            ORDER BY a.created_at DESC
            LIMIT 10";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
