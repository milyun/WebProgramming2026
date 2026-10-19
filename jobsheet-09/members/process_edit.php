<?php
session_start();
require __DIR__ . '/../includes/connection.php';

$id = $_POST['id'] ?? null;
$name = trim($_POST['name'] ?? '');
$memberNo = trim($_POST['member_no'] ?? '');
$address = trim($_POST['address'] ?? '');
$phone = trim($_POST['phone'] ?? '');

if (!$id) {
    header('Location: list.php');
    exit;
}

$errors = [];
if ($name === '') {
    $errors[] = "Name is required.";
}
if ($memberNo === '') {
    $errors[] = "Member No. is required.";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => implode(' ', $errors)];
    header('Location: edit.php?id=' . urlencode($id));
    exit;
}

$stmt = $pdo->prepare(
    "UPDATE anggota SET nama = :name, no_anggota = :member_no,
     alamat = :address, no_hp = :phone WHERE id = :id"
);
$stmt->execute([
    'name' => $name,
    'member_no' => $memberNo,
    'address' => $address,
    'phone' => $phone,
    'id' => $id,
]);

$_SESSION['flash'] = ['type' => 'success', 'message' => 'Member updated successfully.'];
header('Location: list.php');
exit;
