<?php
// filepath: c:\wamp64\www\2nd_year\Task_Management_System\configs\taskmanager.php
require_once 'db.php';

class TaskManager
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function createTask($title, $description, $priority, $due_date, $user_id)
    {
        $query = "INSERT INTO tasks (title, description, priority, status, due_date, user_id, created_at, updated_at) 
                VALUES (?, ?, ?, 'pending', ?, ?, NOW(), NOW())";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$title, $description, $priority, $due_date, $user_id]);
    }

    public function assignTask($title, $description, $priority, $due_date, $user_id, $admin_id)
    {
        $query = "INSERT INTO tasks (title, description, priority, status, due_date, user_id, assigned_by, created_at, updated_at) 
                VALUES (?, ?, ?, 'pending', ?, ?, ?, NOW(), NOW())";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$title, $description, $priority, $due_date, $user_id, $admin_id]);
    }

    public function readTask()
    {
        $query = "SELECT * FROM tasks ORDER BY created_at DESC";
        $result = $this->conn->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTasksCount()
    {
        $sql = "SELECT COUNT(*) AS count FROM tasks";
        $result = $this->conn->query($sql);
        return $result->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public function getCompletedTasksCount()
    {
        $sql = "SELECT COUNT(*) AS count FROM tasks WHERE status = 'completed'";
        $result = $this->conn->query($sql);
        return $result->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public function getUsersCount()
    {
        $sql = "SELECT COUNT(*) AS count FROM users"; // <-- fixed table name
        $result = $this->conn->query($sql);
        return $result->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public function getTasksAssignedByAdmin($admin_id)
    {
        $sql = "SELECT t.*, u.username AS assigned_to FROM tasks t JOIN users u ON t.user_id = u.id WHERE t.assigned_by = ?"; // <-- fixed table name
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$admin_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCompletedTasksByAdmin($admin_id)
    {
        $sql = "SELECT t.*, u.username AS assigned_to, t.completed_date, t.status
            FROM tasks t
            JOIN users u ON t.user_id = u.id
            WHERE t.assigned_by = ? AND t.status = 'completed'
            ORDER BY t.due_date DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$admin_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserTasks($user_id)
    {
        $sql = "SELECT t.*, a.username AS assigned_by_name, t.assigned_by
            FROM tasks t
            LEFT JOIN users a ON t.assigned_by = a.id
            WHERE t.user_id = ?
            ORDER BY FIELD(t.priority, 'High', 'Medium', 'Low')";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserTasksBySearch($user_id, $search = '', $status = '')
    {
        $sql = "SELECT t.*, a.username AS assigned_by_name
            FROM tasks t
            LEFT JOIN users a ON t.assigned_by = a.id
            WHERE t.user_id = ?";
        $params = [$user_id];

        if ($status !== '') {
            $sql .= " AND t.status = ?";
            $params[] = $status;
        }
        if ($search !== '') {
            $sql .= " AND (t.title LIKE ? OR t.description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        $sql .= " ORDER BY FIELD(t.priority, 'High', 'Medium', 'Low')";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCompletedTasksByUser($user_id)
    {
        $sql = "SELECT t.*, u.username AS assigned_to, t.completed_date
            FROM tasks t
            JOIN users u ON t.user_id = u.id
            WHERE t.user_id = ? AND t.status = 'completed'
            ORDER BY t.due_date DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteTaskById($task_id, $user_id)
    {
        $sql = "DELETE FROM tasks WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$task_id, $user_id]);
    }

    public function isTaskSelfAssigned($task_id, $user_id)
    {
        $sql = "SELECT assigned_by FROM tasks WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$task_id, $user_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // Only allow delete if assigned_by is the user themselves
        return $row && $row['assigned_by'] == $user_id;
    }
}
