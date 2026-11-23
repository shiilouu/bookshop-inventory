<?php
$pageTitle = 'Stock-In (Receiving)';
require 'includes/header.php';
require 'includes/sidebar.php';

$msg = null; $type = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $bookId = (int)($_POST['book_id'] ?? 0);
  $qty    = (int)($_POST['quantity'] ?? 0);
  $cost   = (float)($_POST['unit_cost'] ?? 0);
  $book   = book_by_id($books, $bookId);
  if (!$book)            { $msg = 'Choose a book from the list.'; $type = 'error'; }
  elseif ($qty < 1)      { $msg = 'Quantity must be at least 1.'; $type = 'error'; }
  elseif ($cost <= 0)    { $msg = 'Enter the unit cost per copy.'; $type = 'error'; }
  else {
    // TODO (backend): INSERT into transactions + UPDATE books SET quantity = quantity + :qty
    $msg = "Received $qty copies of \"{$book['title']}\". Stock is now " . ($book['quantity'] + $qty) . '.'; $type = 'success';
  }
}
?>
<header class="page-head">
  <h1>Receive stock</h1>
  <p>Record books delivered by a supplier. Stock goes up as soon as you save.</p>
</header>

<?php if ($msg): ?>
  <div class="alert alert-<?= $type ?>" role="<?= $type === 'error' ? 'alert' : 'status' ?>"><?= e($msg) ?></div>
<?php endif; ?>

<form class="panel form-grid" method="post" novalidate data-validate="stock-in">
  <div class="field span-2">
    <label for="book_id">Book</label>
    <select id="book_id" name="book_id" required>
      <option value="">Select a book</option>
      <?php foreach ($books as $b): ?>
        <option value="<?= $b['id'] ?>" data-stock="<?= $b['quantity'] ?>" data-min="<?= $b['minimum_stock'] ?>">
          <?= e($b['title']) ?> (<?= e($b['isbn']) ?>)
        </option>
      <?php endforeach; ?>
    </select>
    <small class="hint" id="stock-hint">Pick a book to see its current stock.</small>
    <small class="error-text" data-error-for="book_id"></small>
  </div>

  <div class="field">
    <label for="quantity">Quantity received</label>
    <input type="number" id="quantity" name="quantity" min="1" step="1" inputmode="numeric" required>
    <small class="error-text" data-error-for="quantity"></small>
  </div>

  <div class="field">
    <label for="unit_cost">Unit cost (₱)</label>
    <input type="number" id="unit_cost" name="unit_cost" min="0.01" step="0.01" inputmode="decimal" required>
    <small class="error-text" data-error-for="unit_cost"></small>
  </div>

  <div class="field">
    <label for="date">Date received</label>
    <input type="date" id="date" name="date" value="<?= date('Y-m-d') ?>" required>
    <small class="error-text" data-error-for="date"></small>
  </div>

  <div class="field">
    <label for="supplier">Supplier</label>
    <input type="text" id="supplier" name="supplier" maxlength="80" placeholder="e.g. National Book Store">
  </div>

  <div class="field span-2">
    <label for="note">Note (optional)</label>
    <textarea id="note" name="note" rows="2" maxlength="200"></textarea>
  </div>

  <div class="actions span-2">
    <button type="submit" class="btn btn-primary">Save stock-in</button>
    <button type="reset" class="btn btn-ghost">Clear form</button>
  </div>
</form>
<?php require 'includes/footer.php'; ?>
