<?php
require_once '../config/config.php';
require_once '../classes/Message.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiver_id = $_POST['receiver_id'] ?? '';
    $message = $_POST['message'] ?? '';
    
    if (empty($receiver_id) || empty($message)) {
        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
        exit;
    }
    
    if (strlen($message) > MAX_MESSAGE_LENGTH) {
        echo json_encode(['success' => false, 'error' => 'Message too long']);
        exit;
    }
    
    $messageObj = new Message();
    $messageObj->sender_id = $_SESSION['user_id'];
    $messageObj->receiver_id = $receiver_id;
    $messageObj->message = $message;
    
    if ($messageObj->send()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to send message']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>