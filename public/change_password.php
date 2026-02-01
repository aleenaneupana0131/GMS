<?php
require_once '../config/db.php';
require_once '../includes/functions.php';
require_login();

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_token($_POST['csrf_token']);
    
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    // 1. Verify current password
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if ($user && password_verify($current, $user['password'])) {
        if ($new === $confirm) {
            if (strlen($new) >= 6) {
                // 2. Update password
                $hash = password_hash($new, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hash, $_SESSION['user_id']]);
                $success = "Password updated successfully! ✨";
            } else { $error = "New password must be at least 6 characters."; }
        } else { $error = "Passwords do not match."; }
    } else { $error = "Invalid current password."; }
}
?>
<?php include '../includes/header.php'; ?>

<div class="content-card" style="max-width: 400px; margin: 40px auto; padding: 40px;">
    <div style="text-align: center; margin-bottom: 25px;">
        <h2 style="color: var(--primary-pink); margin: 0; font-size: 22px;">Change Password 🔒</h2>
        <p style="color: #999; font-size: 13px;">Update your admin credentials</p>
    </div>

    <?php if($error): ?>
        <p style="color:#ff477e; font-size:13px; background: #fff0f3; padding: 12px; border-radius: 12px; margin-bottom: 20px;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if($success): ?>
        <p style="color:#2e7d32; font-size:13px; background: #e8f5e9; padding: 12px; border-radius: 12px; margin-bottom: 20px;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
        
        <label>Current Password</label>
        <input type="password" name="current_password" required>

        <label>New Password</label>
        <input type="password" name="new_password" required>

        <label>Confirm New Password</label>
        <input type="password" name="confirm_password" required>

        <button type="submit" class="btn btn-main" style="width: 100%; margin-top: 10px;">Update Password</button>
        <a href="dashboard.php" style="display: block; text-align: center; margin-top: 20px; color: #999; text-decoration: none; font-size: 13px;">Back to Dashboard</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
