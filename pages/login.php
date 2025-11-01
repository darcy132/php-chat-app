<?php
require_once '../config/config.php';
require_once '../includes/auth.php';

redirectIfLoggedIn();
?>
<?php include '../includes/header.php'; ?>

<div class="auth-container">
    <div class="auth-form">
        <h2>Login to Chat</h2>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="success"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>
        
        <form id="loginForm">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn">Login</button>
        </form>
        
        <p style="text-align: center; margin-top: 20px;">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#loginForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '../ajax/login.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    window.location.href = 'chat.php';
                } else {
                    alert('Error: ' + response.error);
                }
            },
            error: function() {
                alert('Error during login');
            }
        });
    });
});
</script>

<?php include '../includes/footer.php'; ?>