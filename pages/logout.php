<?php
require_once '../config/config.php';
require_once '../classes/User.php';

// Update user's online status
if (isset($_SESSION['user_id'])) {
    $userObj = new User();
    $userObj->setOnlineStatus($_SESSION['user_id'], 0);
}

// Destroy session
session_destroy();

// Redirect to login page
header("Location: login.php?success=Logged out successfully");
exit;
?>