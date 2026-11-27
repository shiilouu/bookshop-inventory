<?php
/*
 * Low-stock notifications (back-end)
 * Owner: Ace Molbog (group3-dashboard-db)
 *
 * Reads from the view v_low_stock_books (columns: book_id, title, quantity, minimum_stock).
 * Include AFTER config/db.php so $conn exists:
 *   require_once __DIR__ . '/low-stock-notifications.php';
 */

/** Returns low-stock books, most critical first. Pass $limit = 0 for all. */
function getLowStockBooks(mysqli $conn, int $limit = 0): array
{
    $sql = 'SELECT book_id, title, quantity, minimum_stock
            FROM v_low_stock_books
            ORDER BY (quantity - minimum_stock) ASC, title ASC';
    if ($limit > 0) {
        $sql .= ' LIMIT ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $limit);
    } else {
        $stmt = $conn->prepare($sql);
    }
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $rows;
}

/** Number of books at or below minimum stock (for the sidebar badge). */
function getLowStockCount(mysqli $conn): int
{
    $stmt = $conn->prepare('SELECT COUNT(*) AS total FROM v_low_stock_books');
    $stmt->execute();
    $total = (int)$stmt->get_result()->fetch_assoc()['total'];
    $stmt->close();
    return $total;
}

/** Number of books with zero copies left. */
function getOutOfStockCount(mysqli $conn): int
{
    $stmt = $conn->prepare('SELECT COUNT(*) AS total FROM v_low_stock_books WHERE quantity = 0');
    $stmt->execute();
    $total = (int)$stmt->get_result()->fetch_assoc()['total'];
    $stmt->close();
    return $total;
}

/**
 * Builds the notification text, or null when nothing is low.
 * Example: "3 books are below minimum stock (1 is out of stock)."
 */
function getLowStockMessage(mysqli $conn): ?string
{
    $low = getLowStockCount($conn);
    if ($low === 0) {
        return null;
    }
    $out = getOutOfStockCount($conn);
    $msg = $low . ($low === 1 ? ' book is' : ' books are') . ' below minimum stock';
    if ($out > 0) {
        $msg .= ' (' . $out . ($out === 1 ? ' is' : ' are') . ' out of stock)';
    }
    return $msg . '.';
}

/**
 * Shows the notification once per login session, using the shared
 * $_SESSION['error_msg'] key so the layout's existing message display picks it up.
 * Call from the dashboard before header.php is included.
 */
function notifyLowStockOnce(mysqli $conn): void
{
    if (!empty($_SESSION['low_stock_notified'])) {
        return;
    }
    $msg = getLowStockMessage($conn);
    if ($msg !== null) {
        $_SESSION['error_msg'] = $msg;
    }
    $_SESSION['low_stock_notified'] = true;
}
