<?php
$page_title = "Edit Book";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/connection.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM buku WHERE id = :id");
$stmt->execute(['id' => $id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    header('Location: list.php');
    exit;
}
?>
        <section>
            <h2>Edit Book</h2>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['message']; ?></p>
            <?php endif; ?>

            <form id="form-add" method="post" action="process_edit.php">
                <input type="hidden" name="id" value="<?php echo $book['id']; ?>">
                <p>
                    <label for="title">Title</label><br>
                    <input type="text" id="title" name="title" value="<?php echo $book['judul']; ?>" required>
                </p>
                <p>
                    <label for="author">Author</label><br>
                    <input type="text" id="author" name="author" value="<?php echo $book['pengarang']; ?>" required>
                </p>
                <p>
                    <label for="year">Year Published</label><br>
                    <input type="number" id="year" name="year" min="1900" max="2026" value="<?php echo $book['tahun']; ?>" required>
                </p>
                <p>
                    <label for="isbn">ISBN</label><br>
                    <input type="text" id="isbn" name="isbn" value="<?php echo $book['isbn']; ?>">
                </p>
                <p>
                    <label for="stock">Stock</label><br>
                    <input type="number" id="stock" name="stock" min="0" value="<?php echo $book['stok']; ?>" required>
                </p>
                <p>
                    <label for="category">Category</label><br>
                    <select id="category" name="category">
                        <?php foreach (['fiksi' => 'Fiction', 'non-fiksi' => 'Non-Fiction', 'referensi' => 'Reference'] as $value => $label): ?>
                        <option value="<?php echo $value; ?>" <?php echo $book['kategori'] === $value ? 'selected' : ''; ?>><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p>
                    <button type="submit">Update</button>
                </p>
            </form>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
