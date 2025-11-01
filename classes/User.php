<?php
require_once 'Database.php';

class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $email;
    public $password;
    public $avatar;
    public $is_online;
    public $last_seen;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function register() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET username=:username, email=:email, password=:password";
        
        $stmt = $this->conn->prepare($query);
        
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function login() {
        $query = "SELECT id, username, password FROM " . $this->table_name . " 
                 WHERE username = :username LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(password_verify($this->password, $row['password'])) {
                $this->id = $row['id'];
                $this->username = $row['username'];
                
                // Update online status
                $this->setOnlineStatus($this->id, 1);
                
                return true;
            }
        }
        return false;
    }

    public function setOnlineStatus($user_id, $status) {
        $query = "UPDATE " . $this->table_name . " 
                 SET is_online = :status, last_seen = NOW() 
                 WHERE id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":user_id", $user_id);
        return $stmt->execute();
    }

    public function getOnlineUsers() {
        $query = "SELECT id, username, avatar FROM " . $this->table_name . " 
                 WHERE is_online = 1 AND id != :current_user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":current_user_id", $_SESSION['user_id']);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($user_id) {
        $query = "SELECT id, username, avatar, is_online FROM " . $this->table_name . " 
                 WHERE id = :user_id LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function usernameExists() {
        $query = "SELECT id FROM " . $this->table_name . " 
                 WHERE username = :username LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
}
?>