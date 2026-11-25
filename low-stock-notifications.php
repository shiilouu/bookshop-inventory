<?php
session_start();
require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'] ?? '', ['admin', 'staff'], true)) {
    header('Location: login.php');
    exit;
}

$errorMsg = '';
$lowStockList = [];
$result = $conn->query("SELECT * FROM v_low_stock_books ORDER BY stock_quantity ASC, title ASC");
if ($result) {
    $lowStockList = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
} else {
    $errorMsg = 'Could not load low-stock books.';
}

if (($_GET['format'] ?? '') === 'json') {
    header('Content-Type: application/json');
    echo json_encode([
        'count' => count($lowStockList),
        'items' => $lowStockList,
    ]);
    exit;
}

$lowStockCount = count($lowStockList);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Low Stock Notifications - Bookshop Inventory System</title>
</head>
<body>
    <h1>Low Stock Notifications</h1>

    <?php if ($errorMsg !== ''): ?>
        <p class="error"><?= htmlspecialchars($errorMsg) ?></p>
    <?php elseif ($lowStockCount === 0): ?>
        <p>No low-stock books right now.</p>
    <?php else: ?>
        <p><?= $lowStockCount ?> book(s) at or below minimum stock.</p>
        <table>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Stock</th>
                <th>Minimum</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php foreach ($lowStockList as $book): ?>
                <?php $isOut = (int) $book['stock_quantity'] === 0; ?>
                <tr class="<?= $isOut ? 'out-of-stock' : 'low-stock' ?>">
                    <td><?= htmlspecialchars($book['title']) ?></td>
                    <td><?= htmlspecialchars($book['category_name']) ?></td>
                    <td><?= (int) $book['stock_quantity'] ?></td>
                    <td><?= (int) $book['minimum_stock'] ?></td>
                    <td><?= $isOut ? 'Out of stock' : 'Low stock' ?></td>
                    <td><a href="stock-in.php?book_id=<?= (int) $book['book_id'] ?>">Restock</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <p><a href="admin-dashboard.php">Back to dashboard</a></p>
</body>
</html>
