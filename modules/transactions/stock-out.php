<?php
/* Stock-Out (Sales) page */
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/sample-data.php';
$pageTitle = 'Stock-Out (Sales)';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';
$successMsg = $_SESSION['success_msg'] ?? ''; $errorMsg = $_SESSION['error_msg'] ?? '';
unset($_SESSION['success_msg'], $_SESSION['error_msg']);
?>
<header class="page-head">
  <h1>Record a sale</h1>
  <p>Take books out of stock when they are sold. You can't sell more than what is on the shelf.</p>
</header>

<?php if ($successMsg !== ''): ?><div class="alert alert-success" role="status"><?= e($successMsg) ?></div><?php endif; ?>
<?php if ($errorMsg !== ''): ?><div class="alert alert-error" role="alert"><?= e($errorMsg) ?></div><?php endif; ?>

<form class="panel form-grid" method="post" action="transaction-action.php" novalidate data-validate="stock-out">
  <input type="hidden" name="transaction_type" value="stock_out">

  <div class="field span-2">
    <label for="book_id">Book</label>
    <select id="book_id" name="book_id" required>
      <option value="">Select a book</option>
      <?php foreach ($bookList as $book): ?>
        <option value="<?= (int)$book['book_id'] ?>" data-stock="<?= (int)$book['quantity'] ?>" data-min="<?= (int)$book['minimum_stock'] ?>" data-price="<?= e($book['price']) ?>" <?= $book['quantity'] < 1 ? 'disabled' : '' ?>>
          <?= e($book['title']) ?> (<?= (int)$book['quantity'] ?> in stock)
        </option>
      <?php endforeach; ?>
    </select>
    <small class="hint" id="stock-hint">Pick a book to see its current stock.</small>
    <small class="error-text" data-error-for="book_id"></small>
  </div>

  <div class="field span-2">
    <label for="quantity">Quantity sold</label>
    <input type="number" id="quantity" name="quantity" min="1" step="1" inputmode="numeric" required>
    <small class="error-text" data-error-for="quantity"></small>
  </div>

  <div class="field span-2 total-line">
    <span>Sale total</span><strong id="sale-total">₱0.00</strong>
  </div>

  <div class="field span-2">
    <label for="remarks">Remarks (optional)</label>
    <textarea id="remarks" name="remarks" rows="2" maxlength="200"></textarea>
  </div>

  <div class="actions span-2">
    <button type="submit" class="btn btn-primary">Save sale</button>
    <button type="reset" class="btn btn-ghost">Clear form</button>
  </div>
</form>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
