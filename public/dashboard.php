<?php 
require_once '../config/db.php'; 
require_once '../includes/functions.php'; 
require_login(); 

// Fetch stats
$total_stmt = $pdo->query("SELECT COUNT(*) FROM members");
$total_members = $total_stmt->fetchColumn();

$today = date('Y-m-d');
$active_stmt = $pdo->prepare("SELECT COUNT(*) FROM members WHERE end_date >= ?");
$active_stmt->execute([$today]);
$active_members = $active_stmt->fetchColumn();
?>

<?php include '../includes/header.php'; ?>

<!-- Stats Overview -->
<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Members</h3>
        <div class="value"><?php echo $total_members; ?></div>
    </div>
    <div class="stat-card">
        <h3>Active Members</h3>
        <div class="value"><?php echo $active_members; ?></div>
    </div>
</div>

<div class="content-card">

    <div class="search-container">
        <input type="text" id="ajaxSearch" class="search-input" placeholder="Search my members...">
        <select id="statusFilter" style="width: auto;">

            <option value="all">All Status</option>
            <option value="active">Active Members</option>
            <option value="expired">Expired Members</option>
        </select>
        <div class="toggle-group">

            <button id="btnList" class="view-btn">List</button>
            <button id="btnCard" class="view-btn">Cards</button>
        </div>
        <a href="add.php" class="btn btn-main">Register New 💖</a>
    </div>

    <div id="listView">
        <table>
            <thead>
                <tr>
                    <th>PHOTO</th><th>MEMBER</th><th>JOINED</th><th>EXPIRY</th><th>STATUS</th><th>ACTIONS</th>
                </tr>
            </thead>

            <tbody id="memberTableBody"></tbody>
        </table>
    </div>

    <div id="cardView" class="view-cards hidden"></div>
</div>

<script src="../assets/js/script.js"></script>
<?php include '../includes/footer.php'; ?>