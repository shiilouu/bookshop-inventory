<?php
session_start();
require_once _DIR_ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';
    $categoryName = trim($_POST['category_name'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($action === 'add') {

        $stmt = $conn->prepare(
            "INSERT INTO categories (category_name, description) VALUES (?, ?)"
        );
        $stmt->bind_param("ss", $categoryName, $description);

        if ($stmt->execute()) {
            $_SESSION['success_msg'] = "Category added successfully.";
        } else {
            $_SESSION['error_msg'] = "Failed to add category.";
        }

    } elseif ($action === 'update') {

        $categoryId = intval($_POST['category_id']);

        $stmt = $conn->prepare(
            "UPDATE categories SET category_name = ?, description = ? WHERE category_id = ?"
        );
        $stmt->bind_param("ssi", $categoryName, $description, $categoryId);

        if ($stmt->execute()) {
            $_SESSION['success_msg'] = "Category updated successfully.";
        } else {
            $_SESSION['error_msg'] = "Failed to update category.";
        }

    } elseif ($action === 'delete') {

        $categoryId = intval($_POST['category_id']);

        $stmt = $conn->prepare(
            "DELETE FROM categories WHERE category_id = ?"
        );
        $stmt->bind_param("i", $categoryId);

        if ($stmt->execute()) {
            $_SESSION['success_msg'] = "Category deleted successfully.";
        } else {
            $_SESSION['error_msg'] = "Failed to delete category.";
        }
    }

    header("Location: manage-categories.php");
    exit;
}
?>
