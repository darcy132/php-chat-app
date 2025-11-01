<?php
require_once '../config/config.php';
require_once '../classes/User.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$userObj = new User();
$onlineUsers = $userObj->getOnlineUsers();

echo json_encode([
    'success' => true,
    'users' => $onlineUsers
]);
?>