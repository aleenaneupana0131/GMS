<?php 
require 'db.php'; 
include 'header.php'; 

// Add Item Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_item'])) {
    $stmt = $pdo->prepare("INSERT INTO menu_items (name, category, price) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['name'], $_POST['category'], $_POST['price']]);
}

// Delete Logic
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM menu_items WHERE id=?")->execute([$_GET['delete']]);
    echo "<script>window.location='menu.php';</script>";
}
?>

<div class="row">
    <!-- Add Item Form -->
    <div class="col-md-4">
        <div class="card p-3">
            <h4>Add Menu Item</h4>
            <form method="POST">
                <div class="mb-2">
                    <label>Item Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Category</label>
                    <select name="category" class="form-control">
                        <option>Starter</option>
                        <option>Main</option>
                        <option>Drink</option>
                        <option>Dessert</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Price ($)</label>
                    <input type="number" step="0.01" name="price" class="form-control" required>
                </div>
                <button type="submit" name="add_item" class="btn btn-danger w-100">Add to Menu</button>
            </form>
        </div>
    </div>

    <!-- Menu List -->
    <div class="col-md-8">
        <table class="table table-bordered bg-white">
            <thead><tr><th>Name</th><th>Category</th><th>Price</th><th>Action</th></tr></thead>
            <tbody>
                <?php
                $items = $pdo->query("SELECT * FROM menu_items");
                while ($row = $items->fetch()) {
                    echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['category']}</td>
                        <td>\${$row['price']}</td>
                        <td><a href='menu.php?delete={$row['id']}' class='btn btn-sm btn-outline-danger'>Delete</a></td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</div></body></html>