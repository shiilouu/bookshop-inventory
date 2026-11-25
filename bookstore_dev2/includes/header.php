<?php /* STUB - replace with Kimberly's shared header.php. Set $pageTitle before including. */
require_once __DIR__ . '/sample_data.php';
$pageTitle = $pageTitle ?? 'Book Inventory'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?> | Book Inventory</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Literata:wght@600;700&family=Public+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/transactions.css">
</head>
<body>
<button class="menu-toggle" aria-label="Toggle menu" aria-expanded="false">Menu</button>
<div class="app">
