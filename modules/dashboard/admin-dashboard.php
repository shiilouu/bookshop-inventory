<?php
/* Admin dashboard page */
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../transactions/sample-data.php';
$pageTitle = 'Admin Dashboard';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';

$lowStock   = getLowStockBooks($bookList);
$bestSellers = getBestSellers($saleList, $bookList);
$dailySales = getDailySales($saleList);
$maxDaily   = $dailySales ? max($dailySales) : 1;
$maxSold    = $bestSellers ? max(array_column($bestSellers, 'sold')) : 1;
$totalUnits = array_sum(array_column($bookList, 'quantity'));
$totalSales = array_sum($dailySales);
?>
<header class="page-head">
  <h1>Dashboard</h1>
  <p>Stock levels and sales at a glance.</p>
</header>

<section class="cards" aria-label="Summary">
  <div class="card"><span class="card-label">Book titles</span><strong><?= count($bookList) ?></strong></div>
  <div class="card"><span class="card-label">Copies in stock</span><strong><?= number_format($totalUnits) ?></strong></div>
  <div class="card <?= $lowStock ? 'card-alert' : '' ?>"><span class="card-label">Below minimum stock</span><strong><?= count($lowStock) ?></strong></div>
  <div class="card"><span class="card-label">Total sales</span><strong><?= peso($totalSales) ?></strong></div>
</section>

<div class="dash-grid">
  <section class="panel">
    <div class="panel-head"><h2>Low stock</h2><a href="../transactions/low-stock-alerts.php">See all</a></div>
    <?php if (!$lowStock): ?><p class="empty">Nothing is running low.</p><?php else: ?>
    <ul class="plain-list">
      <?php foreach (array_slice($lowStock, 0, 5) as $book): ?>
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
        <?php foreach (array_reverse($dailySales, true) as $date => $amount): ?>
          <tr>
            <td><?= e(date('M j, Y', strtotime($date))) ?></td>
            <td class="bar-cell"><div class="bar"><div style="width:<?= round($amount / $maxDaily * 100) ?>%"></div></div></td>
            <td class="num"><?= peso($amount) ?></td>
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
