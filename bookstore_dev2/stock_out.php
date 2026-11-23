<?php
$pageTitle = 'Stock-Out (Sales)';
require 'includes/header.php';
require 'includes/sidebar.php';

$msg = null; $type = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $bookId = (int)($_POST['book_id'] ?? 0);
  $qty    = (int)($_POST['quantity'] ?? 0);
  $book   = book_by_id($books, $bookId);
  if (!$book)                          { $msg = 'Choose a book from the list.'; $type = 'error'; }
  elseif ($qty < 1)                    { $msg = 'Quantity must be at least 1.'; $type = 'error'; }
  elseif ($qty > $book['quantity'])    { $msg = "Only {$book['quantity']} copies of \"{$book['title']}\" are in stock. Lower the quantity."; $type = 'error'; }
  else {
    // TODO (backend): INSERT into transactions + UPDATE books SET quantity = quantity - :qty
    $left  = $book['quantity'] - $qty;
    $total = $qty * $book['price'];
    $msg = "Sold $qty × \"{$book['title']}\" for " . peso($total) . ". $left left in stock.";
    if ($left < $book['minimum_stock']) $msg .= ' This book is now below its minimum stock.';
    $type = 'success';
  }
}
?>
<header class="page-head">
  <h1>Record a sale</h1>
  <p>Take books out of stock when they are sold. You can't sell more than what is on the shelf.</p>
</header>

<?php if ($msg): ?>
  <div class="alert alert-<?= $type ?>" role="<?= $type === 'error' ? 'alert' : 'status' ?>"><?= e($msg) ?></div>
<?php endif; ?>

<form class="panel form-grid" method="post" novalidate data-validate="stock-out">
  <div class="field span-2">
    <label for="book_id">Book</label>
    <select id="book_id" name="book_id" required>
      <option value="">Select a book</option>
      <?php foreach ($books as $b): ?>
        <option value="<?= $b['id'] ?>" data-stock="<?= $b['quantity'] ?>" data-min="<?= $b['minimum_stock'] ?>" data-price="<?= $b['price'] ?>" <?= $b['quantity']<1?'disabled':'' ?>>
          <?= e($b['title']) ?> (<?= $b['quantity'] ?> in stock)
        </option>
      <?php endforeach; ?>
    </select>
    <small class="hint" id="stock-hint">Pick a book to see its current stock.</small>
    <small class="error-text" data-error-for="book_id"></small>
  </div>

  <div class="field">
    <label for="quantity">Quantity sold</label>
    <input type="number" id="quantity" name="quantity" min="1" step="1" inputmode="numeric" required>
    <small class="error-text" data-error-for="quantity"></small>
  </div>

  <div class="field">
    <label for="date">Date of sale</label>
    <input type="date" id="date" name="date" value="<?= date('Y-m-d') ?>" required>
    <small class="error-text" data-error-for="date"></small>
  </div>

  <div class="field span-2 total-line">
    <span>Sale total</span><strong id="sale-total">₱0.00</strong>
  </div>

  <div class="field span-2">
    <label for="note">Note (optional)</label>
    <textarea id="note" name="note" rows="2" maxlength="200"></textarea>
  </div>

  <div class="actions span-2">
    <button type="submit" class="btn btn-primary">Save sale</button>
    <button type="reset" class="btn btn-ghost">Clear form</button>
  </div>
</form>
<?php require 'includes/footer.php'; ?>
