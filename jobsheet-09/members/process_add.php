<?php
session_start();
require __DIR__ . '/../includes/connection.php';

$name = trim($_POST['name'] ?? '');
$memberNo = trim($_POST['member_no'] ?? '');
$address = trim($_POST['address'] ?? '');
$phone = trim($_POST['phone'] ?? '');

$errors = [];
if ($name === '') {
    $errors[] = "Name is required.";
}
if ($memberNo === '') {
    $errors[] = "Member No. is required.";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => implode(' ', $errors)];
    header('Location: add.php');
    exit;
}

$stmt = $pdo->prepare(
    "INSERT INTO anggota (nama, no_anggota, alamat, no_hp)
     VALUES (:name, :member_no, :address, :phone)
     RETURNING id"
);
$stmt->execute([
    'name' => $name,
    'member_no' => $memberNo,
    'address' => $address,
    'phone' => $phone,
]);

$_SESSION['flash'] = ['type' => 'success', 'message' => 'Member added successfully.'];
header('Location: list.php');
exit;
