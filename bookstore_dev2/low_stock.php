<?php
$pageTitle = 'Low-Stock Alerts';
require 'includes/header.php';
require 'includes/sidebar.php';
$low = low_stock_books($books);
?>
<header class="page-head">
  <h1>Low-stock alerts <?php if ($low): ?><span class="badge badge-lg"><?= count($low) ?></span><?php endif; ?></h1>
  <p>Books with fewer copies than their minimum stock. Reorder these first.</p>
</header>

<?php if (!$low): ?>
  <div class="alert alert-success" role="status">All books are at or above their minimum stock.</div>
<?php else: ?>
  <div class="alert alert-warn" role="status"><?= count($low) ?> book<?= count($low)>1?'s are':' is' ?> below minimum stock.</div>
  <div class="alert-list">
    <?php foreach ($low as $b):
      $pct = $b['minimum_stock'] > 0 ? min(100, round($b['quantity'] / $b['minimum_stock'] * 100)) : 0;
      $critical = $b['quantity'] <= $b['minimum_stock'] / 2; ?>
      <article class="stock-card <?= $critical ? 'critical' : '' ?>">
        <div>
          <h2><?= e($b['title']) ?></h2>
          <p class="muted"><?= e($b['author']) ?> · <?= e($b['category']) ?></p>
        </div>
        <div class="gauge" role="img" aria-label="<?= $b['quantity'] ?> of <?= $b['minimum_stock'] ?> minimum">
          <div class="gauge-fill" style="width:<?= $pct ?>%"></div>
        </div>
        <p class="stock-nums"><strong><?= $b['quantity'] ?></strong> left, minimum <?= $b['minimum_stock'] ?>
          <span class="need">Order at least <?= $b['minimum_stock'] - $b['quantity'] ?></span></p>
        <a class="btn btn-small" href="stock_in.php">Receive stock</a>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
<?php require 'includes/footer.php'; ?>
