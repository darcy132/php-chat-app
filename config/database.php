<?php
// Docker 环境下的数据库配置
define('DB_HOST', getenv('DB_HOST') ?: 'db');
define('DB_NAME', getenv('DB_NAME') ?: 'chat_app');
define('DB_USER', getenv('DB_USER') ?: 'phpchat');
define('DB_PASS', getenv('DB_PASS') ?: 'phpchat');
?>