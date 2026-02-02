<?php
require_once '../config/db.php';
require_once '../includes/functions.php';
require_login();

if (!isset($_GET['id'])) { header("Location: dashboard.php"); exit; }

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM members WHERE id = ?");
$stmt->execute([$id]);
$member = $stmt->fetch();

if (!$member) { die("Member not found."); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_token($_POST['csrf_token']);
    
    $name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $start_date = $_POST['start_date'];
    $package = (int)$_POST['package'];

    // ONLY recalculate if a package is selected
    if ($package > 0) {
        $end_date = date('Y-m-d', strtotime("$start_date + $package months"));
        $stmt = $pdo->prepare("UPDATE members SET full_name=?, phone=?, start_date=?, end_date=? WHERE id=?");
        $stmt->execute([$name, $phone, $start_date, $end_date, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE members SET full_name=?, phone=?, start_date=? WHERE id=?");
        $stmt->execute([$name, $phone, $start_date, $id]);
    }

    
    header("Location: dashboard.php");
    exit;
}
?>
<?php include '../includes/header.php'; ?>

<!-- Style Wrapper -->
<div class="content-card" style="max-width: 480px; margin: 20px auto; padding: 40px;">
    
    <div style="text-align: center; margin-bottom: 25px;">
        <h2 style="color: var(--primary-pink); margin: 0; font-size: 24px;">Edit Member ✨</h2>
        <p style="color: #999; font-size: 14px;">Update details or renew membership</p>
    </div>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
        
        <div>
            <label>Full Name</label>
            <input type="text" name="full_name" value="<?php echo h($member['full_name']); ?>" required>
        </div>
        
        <div>
            <label>Phone Number</label>
            <input type="text" name="phone" value="<?php echo h($member['phone']); ?>" required>
        </div>
        
        <div style="display: flex; gap: 15px;">
            <div style="flex: 1;">
                <label>Start Date</label>
                <input type="date" name="start_date" value="<?php echo $member['start_date']; ?>" required>
            </div>
            <div style="flex: 1;">
                <label>Update Package</label>
                <select name="package">
                    <option value="0">--- Keep Current ---</option>
                    <option value="1">1 Month</option>
                    <option value="3">3 Months</option>
                    <option value="6">6 Months</option>
                    <option value="12">12 Months</option>
                </select>
            </div>

        </div>
        
        <p style="text-align: center; color: #999; font-size: 12px; margin-top: -10px; margin-bottom: 20px;">
            Current Expiry: <strong style="color: var(--accent-pink);"><?php echo d($member['end_date']); ?></strong>
        </p>


        <button type="submit" class="btn btn-main" style="width: 100%; padding: 15px; font-size: 16px;">
            Update & Renew ✨
        </button>
        
        <a href="dashboard.php" style="display: block; text-align: center; margin-top: 20px; color: #999; text-decoration: none; font-size: 13px;">
            Go Back
        </a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>