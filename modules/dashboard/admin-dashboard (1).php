<?php
/* Admin dashboard page (PHP back-end, reads from the database views) */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/low-stock-notifications.php';

// Admin only
if (($_SESSION['role'] ?? '') !== 'admin') {
    $_SESSION['error_msg'] = 'You do not have access to the dashboard.';
    header('Location: ../../index.php');
    exit;
}

/* Helpers (skipped if the layout already defines them) */
if (!function_exists('e')) {
    function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
}
if (!function_exists('peso')) {
    function peso($amount): string { return '₱' . number_format((float)$amount, 2); }
}

/* Notification (shown once per login) */
notifyLowStockOnce($conn);

/* Summary cards: book titles and copies in stock */
$stmt = $conn->prepare('SELECT COUNT(*) AS titles, COALESCE(SUM(quantity), 0) AS units FROM books');
$stmt->execute();
$summary = $stmt->get_result()->fetch_assoc();
$stmt->close();
$totalTitles = (int)$summary['titles'];
$totalUnits  = (int)$summary['units'];

/* Low stock (view: v_low_stock_books) */
$lowStockCount = getLowStockCount($conn);
$lowStock      = getLowStockBooks($conn, 5);

/* Best sellers (view: v_best_sellers) */
$limit = 5;
$stmt = $conn->prepare('SELECT title, total_sold AS sold FROM v_best_sellers ORDER BY total_sold DESC LIMIT ?');
$stmt->bind_param('i', $limit);
$stmt->execute();
$bestSellers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

/* Daily sales, last 30 days, newest first (view: v_daily_sales) */
$days = 30;
$stmt = $conn->prepare('SELECT sale_date, total_amount FROM v_daily_sales
                        WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                        ORDER BY sale_date DESC');
$stmt->bind_param('i', $days);
$stmt->execute();
$dailySales = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$totalSales = array_sum(array_column($dailySales, 'total_amount'));
$maxDaily   = $dailySales ? max(array_column($dailySales, 'total_amount')) : 1;
$maxSold    = $bestSellers ? max(array_column($bestSellers, 'sold')) : 1;
if ($maxDaily <= 0) { $maxDaily = 1; }
if ($maxSold <= 0)  { $maxSold = 1; }

$pageTitle = 'Admin Dashboard';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
?>
<header class="page-head">
  <h1>Dashboard</h1>
  <p>Stock levels and sales at a glance.</p>
</header>

<section class="cards" aria-label="Summary">
  <div class="card"><span class="card-label">Book titles</span><strong><?= $totalTitles ?></strong></div>
  <div class="card"><span class="card-label">Copies in stock</span><strong><?= number_format($totalUnits) ?></strong></div>
  <div class="card <?= $lowStockCount ? 'card-alert' : '' ?>"><span class="card-label">Below minimum stock</span><strong><?= $lowStockCount ?></strong></div>
  <div class="card"><span class="card-label">Sales (last 30 days)</span><strong><?= peso($totalSales) ?></strong></div>
</section>

<div class="dash-grid">
  <section class="panel">
    <div class="panel-head"><h2>Low stock</h2><a href="../transactions/low-stock-alerts.php">See all</a></div>
    <?php if (!$lowStock): ?><p class="empty">Nothing is running low.</p><?php else: ?>
    <ul class="plain-list">
      <?php foreach ($lowStock as $book): ?>
        <li><span><?= e($book['title']) ?></span><span class="tag tag-low"><?= (int)$book['quantity'] ?> / <?= (int)$book['minimum_stock'] ?></span></li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </section>

  <section class="panel">
    <div class="panel-head"><h2>Best sellers</h2></div>
    <?php if (!$bestSellers): ?><p class="empty">No sales recorded yet.</p><?php else: ?>
    <ol class="bars">
      <?php foreach ($bestSellers as $seller): ?>
        <li>
          <div class="bar-label"><span><?= e($seller['title']) ?></span><strong><?= (int)$seller['sold'] ?> sold</strong></div>
          <div class="bar"><div style="width:<?= round($seller['sold'] / $maxSold * 100) ?>%"></div></div>
        </li>
      <?php endforeach; ?>
    </ol>
    <?php endif; ?>
  </section>

  <section class="panel span-all">
    <div class="panel-head"><h2>Sales report</h2><a href="../transactions/transaction-history.php?transaction_type=stock_out">View sales</a></div>
    <?php if (!$dailySales): ?><p class="empty">No sales recorded yet.</p><?php else: ?>
    <div class="table-wrap">
      <table class="table">
        <thead><tr><th>Date</th><th>Sales</th><th class="num">Amount</th></tr></thead>
        <tbody>
        <?php foreach ($dailySales as $day): ?>
          <tr>
            <td><?= e(date('M j, Y', strtotime($day['sale_date']))) ?></td>
            <td class="bar-cell"><div class="bar"><div style="width:<?= round($day['total_amount'] / $maxDaily * 100) ?>%"></div></div></td>
            <td class="num"><?= peso($day['total_amount']) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot><tr><td colspan="2">Total</td><td class="num"><?= peso($totalSales) ?></td></tr></tfoot>
      </table>
    </div>
    <?php endif; ?>
  </section>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
