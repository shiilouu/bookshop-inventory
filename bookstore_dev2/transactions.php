<?php
$pageTitle = 'Transaction History';
require 'includes/header.php';
require 'includes/sidebar.php';
$rows = array_reverse($transactions);
?>
<header class="page-head">
  <h1>Transaction history</h1>
  <p>Every stock-in and sale, newest first.</p>
</header>

<form class="panel filters" id="tx-filters" onsubmit="return false">
  <div class="field">
    <label for="f-type">Type</label>
    <select id="f-type">
      <option value="all">All transactions</option>
      <option value="in">Stock-In</option>
      <option value="out">Stock-Out</option>
    </select>
  </div>
  <div class="field">
    <label for="f-from">From</label>
    <input type="date" id="f-from">
  </div>
  <div class="field">
    <label for="f-to">To</label>
    <input type="date" id="f-to">
  </div>
  <button type="button" class="btn btn-ghost" id="f-reset">Reset filters</button>
</form>

<p class="result-count" id="tx-count" aria-live="polite"></p>

<div class="table-wrap">
  <table class="table" id="tx-table">
    <thead>
      <tr><th>Date</th><th>Type</th><th>Book</th><th class="num">Qty</th><th class="num">Unit price</th><th class="num">Total</th><th>Recorded by</th></tr>
    </thead>
    <tbody>
    <?php foreach ($rows as $t): $b = book_by_id($books, $t['book_id']); ?>
      <tr data-type="<?= $t['type'] ?>" data-date="<?= $t['date'] ?>">
        <td><?= e(date('M j, Y', strtotime($t['date']))) ?></td>
        <td><span class="tag tag-<?= $t['type'] ?>"><?= $t['type']==='in' ? 'Stock-In' : 'Stock-Out' ?></span></td>
        <td><?= e($b['title'] ?? 'Deleted book') ?></td>
        <td class="num"><?= $t['type']==='in' ? '+' : '−' ?><?= $t['qty'] ?></td>
        <td class="num"><?= peso($t['unit_price']) ?></td>
        <td class="num"><?= peso($t['qty'] * $t['unit_price']) ?></td>
        <td><?= e($t['by']) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <p class="empty" id="tx-empty" hidden>No transactions match these filters. Widen the date range or reset the filters.</p>
</div>
<?php require 'includes/footer.php'; ?>
