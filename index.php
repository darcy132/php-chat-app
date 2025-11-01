<?php
require_once 'config/config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: pages/chat.php");
} else {
    header("Location: pages/login.php");
}
exit;
?>