<?php
require_once '../config/config.php';
require_once '../classes/User.php';
require_once '../includes/auth.php';

redirectIfLoggedIn();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Please fill all fields';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long';
    } else {
        $userObj = new User();
        $userObj->username = $username;
        $userObj->email = $email;
        $userObj->password = $password;
        
        if ($userObj->usernameExists()) {
            $error = 'Username already exists';
        } else {
            if ($userObj->register()) {
                $success = 'Registration successful! You can now login.';
                // Clear form
                $_POST = array();
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>
<?php include '../includes/header.php'; ?>

<div class="auth-container">
    <div class="auth-form">
        <h2>Create Account</h2>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn">Register</button>
        </form>
        
        <p style="text-align: center; margin-top: 20px;">
            Already have an account? <a href="login.php">Login here</a>
        </p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>