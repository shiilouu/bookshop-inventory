<?php
/* Transaction history: filter class and page view */

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../auth/check_auth.php";

class TransactionHistory
{
    // Central filter: every value comes in as a string
    public function filter(
        string $transactionType = "",
        string $dateFrom = "",
        string $dateTo = ""
    ): array {
        /*
            Validation Phase
        */
        global $conn;

        $transactionType = trim($transactionType);
        $dateFrom        = trim($dateFrom);
        $dateTo          = trim($dateTo);

        if ($transactionType !== "" && !preg_match("/^(stock_in|stock_out)$/", $transactionType)) {
            throw new InvalidArgumentException("Invalid transaction type.");
        }

        $dateFrom = $this->validateDate($dateFrom, "Date from");
        $dateTo   = $this->validateDate($dateTo, "Date to");

        if ($dateFrom !== null && $dateTo !== null && $dateFrom > $dateTo) {
            throw new InvalidArgumentException("Date from must not be later than date to.");
        }

        // empty filter becomes NULL so the query skips it
        $transactionType = ($transactionType === "") ? null : $transactionType;

        /*
            Query Phase
        */
        $stmt = $conn->prepare(
            "SELECT st.transaction_id, b.title, u.full_name,
                    st.transaction_type, st.quantity, st.unit_price,
                    st.remarks, st.created_at
             FROM stock_transactions st
             JOIN books b ON st.book_id = b.book_id
             JOIN users u ON st.user_id = u.user_id
             WHERE (? IS NULL OR st.transaction_type = ?)
               AND (? IS NULL OR st.created_at >= ?)
               AND (? IS NULL OR st.created_at < DATE_ADD(?, INTERVAL 1 DAY))
             ORDER BY st.created_at DESC, st.transaction_id DESC"
        );

        $stmt->bind_param(
            "ssssss",
            $transactionType,
            $transactionType,
            $dateFrom,
            $dateFrom,
            $dateTo,
            $dateTo
        );

        $stmt->execute();
        $result = $stmt->get_result();

        $transactionList = [];
        while ($row = $result->fetch_assoc()) {
            // escape every value before it reaches the page
            $transactionList[] = array_map(
                fn($value) => htmlspecialchars(
                    (string) $value,
                    ENT_QUOTES,
                    "UTF-8"
                ),
                $row
            );
        }

        $stmt->close();

        return $transactionList;
    }

    // Returns the date as YYYY-MM-DD
    private function validateDate(string $value, string $label): ?string
    {
        if ($value === "") {
            return null;
        }

        if (!preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $value, $parts)
            || !checkdate((int) $parts[2], (int) $parts[3], (int) $parts[1])) {
            throw new InvalidArgumentException($label . " must be a valid date (YYYY-MM-DD).");
        }

        return $value;
    }
}

/* Page view */
if (basename($_SERVER["SCRIPT_FILENAME"]) === basename(__FILE__)) {

    $pageTitle = "Transaction History";

    $selectedType = trim($_GET["transaction_type"] ?? "");
    $dateFrom     = trim($_GET["date_from"] ?? "");
    $dateTo       = trim($_GET["date_to"] ?? "");

    $transactionList = [];
    $errorMsg        = "";

    try {
        $history         = new TransactionHistory();
        $transactionList = $history->filter($selectedType, $dateFrom, $dateTo);
    } catch (InvalidArgumentException $ex) {
        $errorMsg = $ex->getMessage();
    } catch (Throwable $ex) {
        error_log($ex->getMessage());
        $errorMsg = "Could not load transactions. Please try again.";
    }

    $typeValue = htmlspecialchars($selectedType, ENT_QUOTES, "UTF-8");
    $fromValue = htmlspecialchars($dateFrom, ENT_QUOTES, "UTF-8");
    $toValue   = htmlspecialchars($dateTo, ENT_QUOTES, "UTF-8");

    require_once __DIR__ . "/../../includes/header.php";
    require_once __DIR__ . "/../../includes/sidebar.php";
    ?>
    <header class="page-head">
      <h1>Transaction history</h1>
      <p>Every stock-in and sale, newest first.</p>
    </header>

    <?php if ($errorMsg !== ""): ?>
      <div class="alert alert-error" role="alert"><?= htmlspecialchars($errorMsg, ENT_QUOTES, "UTF-8") ?></div>
    <?php endif; ?>

    <form class="panel filters" method="get" action="transaction-history.php">
      <div class="field">
        <label for="transaction_type">Type</label>
        <select id="transaction_type" name="transaction_type">
          <option value="">All transactions</option>
          <option value="stock_in"  <?= $typeValue === "stock_in"  ? "selected" : "" ?>>Stock-In</option>
          <option value="stock_out" <?= $typeValue === "stock_out" ? "selected" : "" ?>>Stock-Out</option>
        </select>
      </div>
      <div class="field">
        <label for="date_from">From</label>
        <input type="date" id="date_from" name="date_from" value="<?= $fromValue ?>">
      </div>
      <div class="field">
        <label for="date_to">To</label>
        <input type="date" id="date_to" name="date_to" value="<?= $toValue ?>">
      </div>
      <button type="submit" class="btn btn-primary">Apply filters</button>
      <a class="btn btn-ghost" href="transaction-history.php">Reset filters</a>
    </form>

    <p class="result-count" aria-live="polite">
      Showing <?= count($transactionList) ?> transaction<?= count($transactionList) === 1 ? "" : "s" ?>
    </p>

    <div class="table-wrap">
      <?php if (count($transactionList) === 0): ?>
        <p class="empty">No transactions match these filters. Widen the date range or reset the filters.</p>
      <?php else: ?>
      <table class="table">
        <thead>
          <tr>
            <th>Date</th><th>Type</th><th>Book</th>
            <th class="num">Qty</th><th class="num">Unit price</th><th class="num">Total</th>
            <th>Recorded by</th><th>Remarks</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($transactionList as $row):
          $isStockIn = ($row["transaction_type"] === "stock_in");
          $lineTotal = (float) $row["quantity"] * (float) $row["unit_price"];
        ?>
          <tr>
            <td><?= date("M j, Y", strtotime($row["created_at"])) ?></td>
            <td><span class="tag <?= $isStockIn ? "tag-in" : "tag-out" ?>"><?= $isStockIn ? "Stock-In" : "Stock-Out" ?></span></td>
            <td><?= $row["title"] ?></td>
            <td class="num"><?= $isStockIn ? "+" : "−" ?><?= $row["quantity"] ?></td>
            <td class="num">₱<?= number_format((float) $row["unit_price"], 2) ?></td>
            <td class="num">₱<?= number_format($lineTotal, 2) ?></td>
            <td><?= $row["full_name"] ?></td>
            <td><?= $row["remarks"] ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </div>
    <?php
    require_once __DIR__ . "/../../includes/footer.php";
}
