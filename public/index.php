<?php
require_once '../config/db.php';
require_once '../includes/functions.php';
$error = "";
if (isset($_SESSION['user_id'])) { header("Location: dashboard.php"); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch();
    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit;
    } else { $error = "Invalid Login Details"; }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gym Admin Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="background: linear-gradient(135deg, #fbc2eb 0%, #a6c1ee 100%); height: 100vh; display: flex; align-items: center; justify-content: center;">

    <div style="width: 300px; background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); text-align: center;">
        <h2 style="color: #ff85a2; margin-top:0;">Gym Portal</h2>
        <p style="color: #999; font-size: 12px; margin-bottom: 25px;">Please enter admin credentials</p>
        
        <?php if($error): ?>
            <p style="color:#ff477e; font-size:12px; background: #fff0f3; padding: 8px; border-radius: 5px;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn btn-pink" style="width:100%; padding: 12px; margin-top: 10px;">Login</button>
        </form>
    </div>

</body>
</html>