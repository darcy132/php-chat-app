<?php
require_once 'Database.php';

class Message {
    private $conn;
    private $table_name = "messages";

    public $id;
    public $sender_id;
    public $receiver_id;
    public $message;
    public $is_read;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function send() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET sender_id=:sender_id, receiver_id=:receiver_id, message=:message";
        
        $stmt = $this->conn->prepare($query);
        
        $this->sender_id = htmlspecialchars(strip_tags($this->sender_id));
        $this->receiver_id = htmlspecialchars(strip_tags($this->receiver_id));
        $this->message = htmlspecialchars(strip_tags($this->message));
        
        $stmt->bindParam(":sender_id", $this->sender_id);
        $stmt->bindParam(":receiver_id", $this->receiver_id);
        $stmt->bindParam(":message", $this->message);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getMessages($user1_id, $user2_id, $last_message_id = 0) {
        $query = "SELECT m.*, u.username as sender_name 
                 FROM " . $this->table_name . " m 
                 LEFT JOIN users u ON m.sender_id = u.id 
                 WHERE ((m.sender_id = :user1_id AND m.receiver_id = :user2_id) 
                 OR (m.sender_id = :user2_id AND m.receiver_id = :user1_id)) 
                 AND m.id > :last_message_id 
                 ORDER BY m.created_at ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user1_id", $user1_id);
        $stmt->bindParam(":user2_id", $user2_id);
        $stmt->bindParam(":last_message_id", $last_message_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAsRead($message_id) {
        $query = "UPDATE " . $this->table_name . " 
                 SET is_read = 1 
                 WHERE id = :message_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":message_id", $message_id);
        return $stmt->execute();
    }

    public function getUnreadCount($user_id) {
        $query = "SELECT COUNT(*) as unread_count 
                 FROM " . $this->table_name . " 
                 WHERE receiver_id = :user_id AND is_read = 0";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['unread_count'];
    }
}
?>