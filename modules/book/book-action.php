<?php
session_start();
require_once _DIR_ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $isbn = trim($_POST['isbn'] ?? '');
    $categoryId = intval($_POST['category_id'] ?? 0);
    $publisher = trim($_POST['publisher'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 0);

    if ($action === 'add') {

        $stmt = $conn->prepare(
            "INSERT INTO books 
            (title, author, isbn, category_id, publisher, price, quantity)
            VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sssdsii",
            $title,
            $author,
            $isbn,
            $categoryId,
            $publisher,
            $price,
            $quantity
        );

        if ($stmt->execute()) {
            $_SESSION['success_msg'] = "Book added successfully.";
        } else {
            $_SESSION['error_msg'] = "Failed to add book.";
        }

    } elseif ($action === 'update') {

        $bookId = intval($_POST['book_id']);

        $stmt = $conn->prepare(
            "UPDATE books SET
            title = ?,
            author = ?,
            isbn = ?,
            category_id = ?,
            publisher = ?,
            price = ?,
            quantity = ?
            WHERE book_id = ?"
        );

        $stmt->bind_param(
            "sssisdii",
            $title,
            $author,
            $isbn,
            $categoryId,
            $publisher,
            $price,
            $quantity,
            $bookId
        );

        if ($stmt->execute()) {
            $_SESSION['success_msg'] = "Book updated successfully.";
        } else {
            $_SESSION['error_msg'] = "Failed to update book.";
        }

    } elseif ($action === 'delete') {

        $bookId = intval($_POST['book_id']);

        $stmt = $conn->prepare(
            "DELETE FROM books WHERE book_id = ?"
        );

        $stmt->bind_param("i", $bookId);

        if ($stmt->execute()) {
            $_SESSION['success_msg'] = "Book deleted successfully.";
        } else {
            $_SESSION['error_msg'] = "Failed to delete book.";
        }
    }

    header("Location: manage-books.php");
    exit;
}
?>