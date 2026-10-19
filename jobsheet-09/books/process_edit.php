<?php
session_start();
require __DIR__ . '/../includes/connection.php';

$id = $_POST['id'] ?? null;
$title = trim($_POST['title'] ?? '');
$author = trim($_POST['author'] ?? '');
$year = $_POST['year'] ?? '';
$isbn = trim($_POST['isbn'] ?? '');
$stock = $_POST['stock'] ?? '';
$category = trim($_POST['category'] ?? '');

if (!$id) {
    header('Location: list.php');
    exit;
}

$errors = [];
if ($title === '') {
    $errors[] = "Title is required.";
}
if ($author === '') {
    $errors[] = "Author is required.";
}
if (!is_numeric($year) || $year < 1900 || $year > 2026) {
    $errors[] = "Year must be between 1900 and 2026.";
}
if (!is_numeric($stock) || $stock < 0) {
    $errors[] = "Stock cannot be negative.";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => implode(' ', $errors)];
    header('Location: edit.php?id=' . urlencode($id));
    exit;
}

$stmt = $pdo->prepare(
    "UPDATE buku SET judul = :title, pengarang = :author, tahun = :year,
     isbn = :isbn, stok = :stock, kategori = :category WHERE id = :id"
);
$stmt->execute([
    'title' => $title,
    'author' => $author,
    'year' => (int) $year,
    'isbn' => $isbn,
    'stock' => (int) $stock,
    'category' => $category,
    'id' => $id,
]);

$_SESSION['flash'] = ['type' => 'success', 'message' => 'Book updated successfully.'];
header('Location: list.php');
exit;
