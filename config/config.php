<?php
session_start();
require_once 'database.php';

// Application settings
define('SITE_NAME', 'PHP Chat App');
define('MAX_MESSAGE_LENGTH', 1000);
define('MESSAGE_UPDATE_INTERVAL', 2000); // 2 seconds
?>