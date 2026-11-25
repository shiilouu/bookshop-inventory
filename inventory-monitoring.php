<?php
session_start();
require_once _DIR_ . '/config/db.php';

$search = trim($_GET['search'] ?? '');
$categoryId = intval($_GET['category_id'] ?? 0);
$stockStatus = $_GET['stock_status'] ?? '';

$sql = "SELECT 
            books.*,
            categories.category_name
        FROM books
        LEFT JOIN categories 
        ON books.category_id = categories.category_id
        WHERE 1=1";

$params = [];
$types = "";

if (!empty($search)) {
    $sql .= " AND (
        books.title LIKE ? 
        OR books.author LIKE ? 
        OR books.isbn LIKE ?
    )";

    $searchTerm = "%$search%";

    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;

    $types .= "sss";
}

if ($categoryId > 0) {
    $sql .= " AND books.category_id = ?";

    $params[] = $categoryId;
    $types .= "i";
}

if ($stockStatus === 'low') {
    $sql .= " AND books.quantity <= books.minimum_stock";
} elseif ($stockStatus === 'available') {
    $sql .= " AND books.quantity > books.minimum_stock";
} elseif ($stockStatus === 'out') {
    $sql .= " AND books.quantity = 0";
}

$sql .= " ORDER BY books.title ASC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();

$books = $stmt->get_result();

$categories = $conn->query(
    "SELECT * FROM categories ORDER BY category_name ASC"
);
?>