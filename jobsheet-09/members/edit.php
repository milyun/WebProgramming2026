<?php
$page_title = "Edit Member";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/connection.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM anggota WHERE id = :id");
$stmt->execute(['id' => $id]);
$member = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$member) {
    header('Location: list.php');
    exit;
}
?>
        <section>
            <h2>Edit Member</h2>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['message']; ?></p>
            <?php endif; ?>

            <form id="form-add" method="post" action="process_edit.php">
                <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
                <p>
                    <label for="name">Name</label><br>
                    <input type="text" id="name" name="name" value="<?php echo $member['nama']; ?>" required>
                </p>
                <p>
                    <label for="member_no">Member No.</label><br>
                    <input type="text" id="member_no" name="member_no" value="<?php echo $member['no_anggota']; ?>" required>
                </p>
                <p>
                    <label for="address">Address</label><br>
                    <input type="text" id="address" name="address" value="<?php echo $member['alamat']; ?>">
                </p>
                <p>
                    <label for="phone">Phone No.</label><br>
                    <input type="text" id="phone" name="phone" value="<?php echo $member['no_hp']; ?>">
                </p>
                <p>
                    <button type="submit">Update</button>
                </p>
            </form>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
