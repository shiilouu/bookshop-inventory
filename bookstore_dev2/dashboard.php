<?php
$pageTitle = 'Admin Dashboard';
require 'includes/header.php';
require 'includes/sidebar.php';

$low      = low_stock_books($books);
$sellers  = best_sellers($transactions, $books);
$daily    = sales_by_day($transactions);
$maxDaily = $daily ? max($daily) : 1;
$maxSold  = $sellers ? max(array_column($sellers, 'sold')) : 1;
$totalUnits = array_sum(array_column($books, 'quantity'));
$totalSales = array_sum($daily);
?>
<header class="page-head">
  <h1>Dashboard</h1>
  <p>Stock levels and sales at a glance.</p>
</header>

<section class="cards" aria-label="Summary">
  <div class="card"><span class="card-label">Book titles</span><strong><?= count($books) ?></strong></div>
  <div class="card"><span class="card-label">Copies in stock</span><strong><?= number_format($totalUnits) ?></strong></div>
  <div class="card <?= $low ? 'card-alert' : '' ?>"><span class="card-label">Below minimum stock</span><strong><?= count($low) ?></strong></div>
  <div class="card"><span class="card-label">Total sales</span><strong><?= peso($totalSales) ?></strong></div>
</section>

<div class="dash-grid">
  <section class="panel">
    <div class="panel-head"><h2>Low stock</h2><a href="low_stock.php">See all</a></div>
    <?php if (!$low): ?><p class="empty">Nothing is running low.</p><?php else: ?>
    <ul class="plain-list">
      <?php foreach (array_slice($low, 0, 5) as $b): ?>
        <li><span><?= e($b['title']) ?></span><span class="tag tag-low"><?= $b['quantity'] ?> / <?= $b['minimum_stock'] ?></span></li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </section>

  <section class="panel">
    <div class="panel-head"><h2>Best sellers</h2></div>
    <?php if (!$sellers): ?><p class="empty">No sales recorded yet.</p><?php else: ?>
    <ol class="bars">
      <?php foreach ($sellers as $s): ?>
        <li>
          <div class="bar-label"><span><?= e($s['title']) ?></span><strong><?= $s['sold'] ?> sold</strong></div>
          <div class="bar"><div style="width:<?= round($s['sold'] / $maxSold * 100) ?>%"></div></div>
        </li>
      <?php endforeach; ?>
    </ol>
    <?php endif; ?>
  </section>

  <section class="panel span-all">
    <div class="panel-head"><h2>Sales report</h2><a href="transactions.php">View transactions</a></div>
    <?php if (!$daily): ?><p class="empty">No sales recorded yet.</p><?php else: ?>
    <div class="table-wrap">
      <table class="table">
        <thead><tr><th>Date</th><th>Sales</th><th class="num">Amount</th></tr></thead>
        <tbody>
        <?php foreach (array_reverse($daily, true) as $date => $amt): ?>
          <tr>
            <td><?= e(date('M j, Y', strtotime($date))) ?></td>
            <td class="bar-cell"><div class="bar"><div style="width:<?= round($amt / $maxDaily * 100) ?>%"></div></div></td>
            <td class="num"><?= peso($amt) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot><tr><td colspan="2">Total</td><td class="num"><?= peso($totalSales) ?></td></tr></tfoot>
      </table>
    </div>
    <?php endif; ?>
  </section>
</div>
<?php require 'includes/footer.php'; ?>
