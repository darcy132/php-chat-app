<?php
require_once '../config/config.php';
require_once '../classes/Message.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $receiver_id = $_GET['receiver_id'] ?? '';
    $last_message_id = $_GET['last_message_id'] ?? 0;
    
    if (empty($receiver_id)) {
        echo json_encode(['success' => false, 'error' => 'Missing receiver ID']);
        exit;
    }
    
    $messageObj = new Message();
    $messages = $messageObj->getMessages($_SESSION['user_id'], $receiver_id, $last_message_id);
    
    echo json_encode([
        'success' => true,
        'messages' => $messages
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>