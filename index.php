<?php 
require 'db.php'; 
include 'header.php'; 

// Fetch Stats
$total_members = $pdo->query("SELECT COUNT(*) FROM members")->fetchColumn();
$active_members = $pdo->query("SELECT COUNT(*) FROM members WHERE status='Active'")->fetchColumn();
$today_checkins = $pdo->query("SELECT COUNT(*) FROM attendance WHERE DATE(checkin_time) = CURDATE()")->fetchColumn();
$revenue = $pdo->query("SELECT SUM(amount) FROM plans p JOIN members m ON p.id = m.plan_id")->fetchColumn();
?>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Members</h5>
                <p class="card-text display-6"><?php echo $total_members; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Active Members</h5>
                <p class="card-text display-6"><?php echo $active_members; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">Check-ins Today</h5>
                <p class="card-text display-6"><?php echo $today_checkins; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-dark mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Revenue</h5>
                <p class="card-text display-6">$<?php echo number_format($revenue, 2); ?></p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Recent Check-ins</div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead><tr><th>Name</th><th>Time</th></tr></thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT m.full_name, a.checkin_time FROM attendance a JOIN members m ON a.member_id = m.id ORDER BY a.checkin_time DESC LIMIT 5");
                        while ($row = $stmt->fetch()) {
                            echo "<tr><td>{$row['full_name']}</td><td>{$row['checkin_time']}</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>