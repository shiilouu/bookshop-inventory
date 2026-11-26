<?php
/* Low-stock alerts page */
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/sample-data.php';
$pageTitle = 'Low-Stock Alerts';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
$lowStock = getLowStockBooks($bookList);
?>
<header class="page-head">
  <h1>Low-stock alerts <?php if ($lowStock): ?><span class="badge badge-lg"><?= count($lowStock) ?></span><?php endif; ?></h1>
  <p>Books with fewer copies than their minimum stock. Reorder these first.</p>
</header>

<?php if (!$lowStock): ?>
  <div class="alert alert-success" role="status">All books are at or above their minimum stock.</div>
<?php else: ?>
  <div class="alert alert-warn" role="status"><?= count($lowStock) ?> book<?= count($lowStock) > 1 ? 's are' : ' is' ?> below minimum stock.</div>
  <div class="alert-list">
    <?php foreach ($lowStock as $book):
      $percent  = $book['minimum_stock'] > 0 ? min(100, round($book['quantity'] / $book['minimum_stock'] * 100)) : 0;
      $isCritical = $book['quantity'] <= $book['minimum_stock'] / 2; ?>
      <article class="stock-card <?= $isCritical ? 'critical' : '' ?>">
        <div>
          <h2><?= e($book['title']) ?></h2>
          <p class="muted"><?= e($book['author']) ?> · <?= e($book['category']) ?></p>
        </div>
        <div class="gauge" role="img" aria-label="<?= (int)$book['quantity'] ?> of <?= (int)$book['minimum_stock'] ?> minimum">
          <div class="gauge-fill" style="width:<?= (int)$percent ?>%"></div>
        </div>
        <p class="stock-nums"><strong><?= (int)$book['quantity'] ?></strong> left, minimum <?= (int)$book['minimum_stock'] ?>
          <span class="need">Order at least <?= (int)($book['minimum_stock'] - $book['quantity']) ?></span></p>
        <a class="btn btn-small" href="stock-in.php">Receive stock</a>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
