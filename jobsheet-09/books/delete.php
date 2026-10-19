<?php
session_start();
require __DIR__ . '/../includes/connection.php';

// Deliberately accepts POST only (not GET) so that a deletion cannot be
// triggered by accident through a link or a link-preview crawler.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id = $_POST['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM buku WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Book deleted successfully.'];
}

header('Location: list.php');
exit;
