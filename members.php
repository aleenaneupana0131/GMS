<?php 
require 'db.php'; 
include 'header.php'; 

// Handle Add Member
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_member'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $plan_id = $_POST['plan_id'];
    
    // Calculate Expiry
    $stmt = $pdo->prepare("SELECT duration_days FROM plans WHERE id = ?");
    $stmt->execute([$plan_id]);
    $days = $stmt->fetchColumn();
    
    $join_date = date('Y-m-d');
    $expiry_date = date('Y-m-d', strtotime("+$days days"));

    $sql = "INSERT INTO members (full_name, phone, plan_id, join_date, expiry_date) VALUES (?, ?, ?, ?, ?)";
    $pdo->prepare($sql)->execute([$name, $phone, $plan_id, $join_date, $expiry_date]);
    echo "<div class='alert alert-success'>Member added successfully!</div>";
}

// Handle Delete
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM members WHERE id = ?")->execute([$_GET['delete']]);
    echo "<script>window.location.href='members.php';</script>";
}
?>

<div class="row">
    <!-- Add Member Form -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">Add New Member</div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Plan</label>
                        <select name="plan_id" class="form-control">
                            <?php
                            $plans = $pdo->query("SELECT * FROM plans");
                            while($p = $plans->fetch()) {
                                echo "<option value='{$p['id']}'>{$p['name']} - \${$p['amount']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" name="add_member" class="btn btn-primary w-100">Add Member</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Members List -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Member List</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Expiry Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $members = $pdo->query("SELECT * FROM members ORDER BY id DESC");
                        foreach ($members as $m) {
                            $status_class = ($m['status'] == 'Active') ? 'text-success' : 'text-danger';
                            // Auto-update status if expired
                            if ($m['expiry_date'] < date('Y-m-d') && $m['status'] == 'Active') {
                                $pdo->prepare("UPDATE members SET status='Expired' WHERE id=?")->execute([$m['id']]);
                                $m['status'] = 'Expired';
                            }
                            echo "<tr>
                                <td>{$m['id']}</td>
                                <td>{$m['full_name']}</td>
                                <td>{$m['phone']}</td>
                                <td>{$m['expiry_date']}</td>
                                <td class='fw-bold $status_class'>{$m['status']}</td>
                                <td>
                                    <a href='members.php?delete={$m['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                </td>
                            </tr>";
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