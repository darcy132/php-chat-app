<?php
require_once '../config/config.php';
require_once '../includes/auth.php';
require_once '../classes/User.php';

redirectIfNotLoggedIn();

// Update user's online status
$userObj = new User();
$userObj->setOnlineStatus($_SESSION['user_id'], 1);
?>
<?php include '../includes/header.php'; ?>

<div class="chat-container">
    <!-- Users Sidebar -->
    <div class="users-sidebar">
        <div class="user-header">
            <div class="user-info">
                <div class="avatar"><?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></div>
                <div class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
            </div>
            <a href="logout.php" class="logout-btn" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
        
        <div class="online-users">
            <div class="no-users">Loading users...</div>
        </div>
    </div>
    
    <!-- Chat Area -->
    <div class="chat-area">
        <div class="no-chat-selected">
            <div>Select a user to start chatting</div>
        </div>
        
        <div class="chat-header" style="display: none;">
            <div class="avatar"></div>
            <div class="username"></div>
        </div>
        
        <div class="chat-messages" style="display: none;"></div>
        
        <div class="chat-input-area" style="display: none;">
            <input type="text" class="message-input" placeholder="Type a message...">
            <button class="send-btn">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<script>
// Make current user ID available to JavaScript
const currentUserId = <?php echo $_SESSION['user_id']; ?>;
</script>

<?php include '../includes/footer.php'; ?>