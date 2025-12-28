<?php 
require 'db.php'; 
include 'header.php'; 

$msg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search = $_POST['search_term'];
    
    // Find member by ID or Phone
    $stmt = $pdo->prepare("SELECT * FROM members WHERE id = ? OR phone = ?");
    $stmt->execute([$search, $search]);
    $member = $stmt->fetch();

    if ($member) {
        if ($member['status'] == 'Active') {
            // Log Attendance
            $pdo->prepare("INSERT INTO attendance (member_id) VALUES (?)")->execute([$member['id']]);
            $msg = "<div class='alert alert-success'>✅ Check-in Successful: <strong>{$member['full_name']}</strong></div>";
        } else {
            $msg = "<div class='alert alert-danger'>❌ Access Denied: Membership Expired!</div>";
        }
    } else {
        $msg = "<div class='alert alert-warning'>⚠️ Member not found.</div>";
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card text-center">
            <div class="card-header">Member Check-In</div>
            <div class="card-body">
                <?php echo $msg; ?>
                <form method="POST">
                    <div class="mb-3">
                        <input type="text" name="search_term" class="form-control form-control-lg" placeholder="Enter Member ID or Phone Number" autofocus required>
                    </div>
                    <button type="submit" class="btn btn-success btn-lg w-100">Check In</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>