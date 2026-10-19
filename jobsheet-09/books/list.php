<?php
$page_title = "Book List";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/connection.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$perPage = 5;
$page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;
$keyword = trim($_GET['q'] ?? '');

if ($keyword !== '') {
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM buku WHERE judul ILIKE :kw");
    $countStmt->execute(['kw' => '%' . $keyword . '%']);
    $totalRows = $countStmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT * FROM buku WHERE judul ILIKE :kw ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue('kw', '%' . $keyword . '%');
} else {
    $totalRows = $pdo->query("SELECT COUNT(*) FROM buku")->fetchColumn();
    $stmt = $pdo->prepare("SELECT * FROM buku ORDER BY id DESC LIMIT :limit OFFSET :offset");
}
$stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue('offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$bookList = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalPages = max(1, (int) ceil($totalRows / $perPage));
?>
        <section>
            <h2>Book List</h2>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['message']; ?></p>
            <?php endif; ?>

            <div class="search-box">
                <form method="get" action="list.php">
                    <span>
                        <label for="search-input">Search Book Title</label><br>
                        <input type="text" id="search-input" name="q" value="<?php echo $keyword; ?>" placeholder="Type a book title...">
                    </span>
                    <button type="submit">Search</button>
                </form>
            </div>

            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Year</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($bookList)): ?>
                    <tr>
                        <td colspan="5">No matching books found.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($bookList as $book): ?>
                        <tr>
                            <td><?php echo $book['judul']; ?></td>
                            <td><?php echo $book['pengarang']; ?></td>
                            <td><?php echo $book['tahun']; ?></td>
                            <td><?php echo $book['stok']; ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $book['id']; ?>" class="btn-edit">Edit</a>
                                <form class="form-delete" method="post" action="delete.php">
                                    <input type="hidden" name="id" value="<?php echo $book['id']; ?>">
                                    <button type="submit" class="btn-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>

            <nav class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="list.php?page=<?php echo $i; ?><?php echo $keyword !== '' ? '&q=' . urlencode($keyword) : ''; ?>"
                   class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
            </nav>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
