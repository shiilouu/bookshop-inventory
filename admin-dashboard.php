<?php
session_start();
require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: login.php');
    exit;
}

function fetchOne($conn, $sql)
{
    $result = $conn->query($sql);
    if (!$result) {
        return null;
    }
    $row = $result->fetch_assoc();
    $result->free();
    return $row;
}

function fetchAllRows($conn, $sql)
{
    $result = $conn->query($sql);
    if (!$result) {
        return [];
    }
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
    return $rows;
}

$errorMsg = '';

$summary = fetchOne($conn, "SELECT * FROM v_dashboard_summary");
if ($summary === null) {
    $errorMsg = 'Could not load dashboard summary. Check that the views are installed.';
    $summary = [
        'total_books' => 0,
        'total_categories' => 0,
        'total_stock' => 0,
        'low_stock_count' => 0,
        'out_of_stock_count' => 0,
    ];
}

$lowStockList = fetchAllRows($conn, "SELECT * FROM v_low_stock_books ORDER BY stock_quantity ASC LIMIT 5");
$recentTransactions = fetchAllRows($conn, "SELECT * FROM v_recent_transactions LIMIT 10");
$stockByCategory = fetchAllRows($conn, "SELECT * FROM v_stock_by_category ORDER BY category_name ASC");

$fullName = $_SESSION['full_name'] ?? 'Admin';
$lowStockCount = (int) $summary['low_stock_count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bookshop Inventory System</title>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?= htmlspecialchars($fullName) ?></p>

    <?php if ($errorMsg !== ''): ?>
        <p class="error"><?= htmlspecialchars($errorMsg) ?></p>
    <?php endif; ?>

    <section id="summary-cards">
        <div class="card"><h3>Total Books</h3><p><?= (int) $summary['total_books'] ?></p></div>
        <div class="card"><h3>Categories</h3><p><?= (int) $summary['total_categories'] ?></p></div>
        <div class="card"><h3>Total Stock</h3><p><?= (int) $summary['total_stock'] ?></p></div>
        <div class="card"><h3>Low Stock</h3><p><?= $lowStockCount ?></p></div>
        <div class="card"><h3>Out of Stock</h3><p><?= (int) $summary['out_of_stock_count'] ?></p></div>
    </section>

    <section id="low-stock-preview">
        <h2>Low Stock Alerts
            <?php if ($lowStockCount > 0): ?>
                <span class="badge"><?= $lowStockCount ?></span>
            <?php endif; ?>
        </h2>
        <?php if (empty($lowStockList)): ?>
            <p>All books are above their minimum stock.</p>
        <?php else: ?>
            <table>
                <tr><th>Title</th><th>Stock</th><th>Minimum</th></tr>
                <?php foreach ($lowStockList as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars($book['title']) ?></td>
                        <td><?= (int) $book['stock_quantity'] ?></td>
                        <td><?= (int) $book['minimum_stock'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <a href="low-stock-notifications.php">View all</a>
        <?php endif; ?>
    </section>

    <section id="stock-by-category">
        <h2>Stock by Category</h2>
        <table>
            <tr><th>Category</th><th>Books</th><th>Total Stock</th></tr>
            <?php foreach ($stockByCategory as $cat): ?>
                <tr>
                    <td><?= htmlspecialchars($cat['category_name']) ?></td>
                    <td><?= (int) $cat['book_count'] ?></td>
                    <td><?= (int) $cat['total_stock'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <section id="recent-transactions">
        <h2>Recent Transactions</h2>
        <table>
            <tr><th>Date</th><th>Book</th><th>Type</th><th>Qty</th><th>By</th></tr>
            <?php foreach ($recentTransactions as $tx): ?>
                <tr>
                    <td><?= htmlspecialchars($tx['transaction_date']) ?></td>
                    <td><?= htmlspecialchars($tx['title']) ?></td>
                    <td><?= htmlspecialchars($tx['transaction_type']) ?></td>
                    <td><?= (int) $tx['quantity'] ?></td>
                    <td><?= htmlspecialchars($tx['full_name']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
</body>
</html>
