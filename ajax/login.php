<?php
require_once '../config/config.php';
require_once '../classes/User.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'error' => 'Please fill all fields']);
        exit;
    }
    
    $userObj = new User();
    $userObj->username = $username;
    $userObj->password = $password;
    
    if ($userObj->login()) {
        $_SESSION['user_id'] = $userObj->id;
        $_SESSION['username'] = $userObj->username;
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid username or password']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>