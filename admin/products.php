<?php
ob_start();
session_start();
if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] != 1) {
    header("Location: /thakran-electronics/login.php");
    exit();
}

require_once __DIR__ . "/../config/db.php";
$page_title = "Admin Products - Thakran Electronics";
include __DIR__ . "/../includes/header.php";

$update_message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "update_stock") {
    $item_id = (int) ($_POST["item_id"] ?? 0);
    $new_stock = (int) ($_POST["stock"] ?? 0);

    if ($item_id > 0) {
        $stmt = $conn->prepare("UPDATE products SET stock = ? WHERE id = ?");
        $stmt->bind_param("ii", $new_stock, $item_id);
        if ($stmt->execute()) {
            $update_message = "<div class='success'>Stock updated successfully.</div>";
        }
        $stmt->close();
    }

    header("Location: " . ($_SERVER["HTTP_REFERER"] ?? "/thakran-electronics/admin/products.php"));
    exit();
}

$category = trim($_GET["category"] ?? "");
$page = max(1, (int) ($_GET["page"] ?? 1));
$per_page = 10;
$offset = ($page - 1) * $per_page;

$conditions = [];
$params = [];
$types = "";

if ($category !== "") {
    $conditions[] = "category = ?";
    $params[] = $category;
    $types .= "s";
}

$whereSQL = $conditions ? "WHERE " . implode(" AND ", $conditions) : "";

$sqlCount = "SELECT COUNT(*) FROM products $whereSQL";
$stmtCount = $conn->prepare($sqlCount);

if ($types) {
    $stmtCount->bind_param($types, ...$params);
}

$stmtCount->execute();
$stmtCount->bind_result($total_rows);
$stmtCount->fetch();
$stmtCount->close();

$total_pages = max(1, ceil($total_rows / $per_page));

$sql = "SELECT id, name, category, price, stock, image_url
        FROM products
        $whereSQL
        ORDER BY id DESC
        LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);

if ($types !== "") {
    $types .= "ii";
    $params[] = $per_page;
    $params[] = $offset;
    $stmt->bind_param($types, ...$params);
} else {
    $stmt->bind_param("ii", $per_page, $offset);
}

$stmt->execute();
$res = $stmt->get_result();

$categories = ["Laptop", "Mouse", "Keyboard", "Monitor", "Graphics Card", "Memory", "Storage"];
?>

<script src="https://unpkg.com/lucide@latest"></script>
<style>
    .admin-container {
        max-width: 1500px;
        margin: 2.5rem auto;
        padding: 0 1.25rem;
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-title {
        font-family: var(--font-instrument);
        font-size: 2rem;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .success {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
        padding: 0.75rem 1rem;
        border-radius: var(--radius);
        margin-bottom: 1.5rem;
        font-family: var(--font-inter);
    }

    .controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .controls .right-buttons {
        display: flex;
        gap: 0.75rem;
    }

    .select {
        padding: 0.65rem 1rem;
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        background: var(--bg-primary);
        font-family: var(--font-inter);
        font-size: 0.95rem;
        cursor: pointer;
        transition: border-color 0.2s;
    }

    .select:hover {
        border-color: #ccc;
    }

    .select:focus {
        outline: none;
        border-color: var(--button-color);
    }

    .btn {
        padding: 0.65rem 1.2rem;
        border-radius: var(--radius);
        font-family: var(--font-inter);
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        font-weight: 500;
    }

    .btn svg {
        width: 16px;
        height: 16px;
    }

    .btn.primary {
        background: var(--button-color);
        color: white;
    }

    .btn.primary:hover {
        background: #004494;
    }

    .btn.secondary {
        background: var(--bg-secondary);
        color: var(--text-primary);
        border: 1px solid var(--border-color);
    }

    .btn.secondary:hover {
        background: #e8e8e8;
    }

    .btn.view {
        background: #0d6efd;
        color: white;
        padding: 0.5rem 0.85rem;
        font-size: 0.85rem;
    }

    .btn.view:hover {
        background: #0b5ed7;
    }

    .btn.edit {
        background: #198754;
        color: white;
        padding: 0.5rem 0.85rem;
        font-size: 0.85rem;
    }

    .btn.edit:hover {
        background: #157347;
    }

    .btn.delete {
        background: #dc3545;
        color: white;
        padding: 0.5rem 0.85rem;
        font-size: 0.85rem;
    }

    .btn.delete:hover {
        background: #bb2d3b;
    }

    .btn.save {
        background: #0d6efd;
        color: white;
        padding: 0.45rem 0.75rem;
        font-size: 0.8rem;
    }

    .btn.save:hover {
        background: #0b5ed7;
    }

    .table-wrapper {
        background: var(--bg-secondary);
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        box-shadow: var(--shadow);
        padding-bottom: 1px;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1050px;
        font-family: var(--font-inter);
    }

    .admin-table thead {
        background: var(--bg-primary);
    }

    .admin-table th:nth-child(1) {
        width: 70px;
    }

    .admin-table th:nth-child(2) {
        width: 120px;
    }

    .admin-table th:nth-child(3) {
        width: 260px;
    }

    .admin-table th:nth-child(4) {
        width: 160px;
    }

    .admin-table th:nth-child(5) {
        width: 130px;
    }

    .admin-table th:nth-child(6) {
        width: 160px;
    }

    .admin-table th:nth-child(7) {
        width: 100px;
    }

    .admin-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text-primary);
        border-bottom: 2px solid var(--border-color);
        white-space: nowrap;
    }

    .admin-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.9rem;
        color: var(--text-primary);
        white-space: nowrap;
        vertical-align: middle;
    }

    .admin-table tbody tr:hover {
        background: rgba(0, 0, 0, 0.02);
    }

    .table-thumb {
        width: 80px;
        height: 80px;
        object-fit: contain;
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 0.35rem;
    }

    .stock-form {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .stock-input {
        width: 70px;
        padding: 0.45rem 0.6rem;
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
    }

    .stock-input:focus {
        outline: none;
        border-color: var(--button-color);
    }

    .actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--text-secondary);
    }

    .empty-state svg {
        width: 64px;
        height: 64px;
        opacity: 0.3;
        margin-bottom: 1rem;
    }

    .pagination {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }

    .pager {
        padding: 0.6rem 1rem;
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        background: var(--bg-primary);
        text-decoration: none;
        color: var(--text-primary);
        font-size: 0.9rem;
        min-width: 40px;
        text-align: center;
        transition: 0.2s;
    }

    .pager:hover {
        background: var(--bg-secondary);
    }

    .pager.active {
        background: var(--button-color);
        color: white;
    }

    @media (max-width: 768px) {
        .admin-table {
            min-width: 900px;
            font-size: 0.85rem;
        }

        .admin-table th,
        .admin-table td {
            padding: 0.7rem 0.5rem;
        }

        .table-thumb {
            width: 60px;
            height: 60px;
        }

        .actions {
            flex-direction: column;
            gap: 6px;
        }

        .page-title {
            font-size: 1.6rem;
        }
    }
</style>

<section>
    <div class="admin-container">
        <div class="page-header">
            <h1 class="page-title">Manage Products</h1>
        </div>

        <?= $update_message ?>

        <div class="controls">
            <form method="get">
                <select class="select" name="category" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat ?>" <?= $cat === $category ? "selected" : "" ?>>
                            <?= $cat ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>

            <div class="right-buttons">
                <a href="/thakran-electronics/admin/add_product.php" class="btn primary">
                    <i data-lucide="plus"></i> Add Product
                </a>
                <a href="/thakran-electronics/admin/index.php" class="btn secondary">
                    <i data-lucide="layout-dashboard"></i> Dashboard
                </a>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($res->num_rows > 0): ?>
                        <?php while ($p = $res->fetch_assoc()): ?>
                            <tr>
                                <td><?= $p["id"] ?></td>

                                <td>
                                    <img src="/thakran-electronics/assets/images/products/<?= $p["image_url"] ?>"
                                        class="table-thumb"
                                        alt="<?= htmlspecialchars($p["name"]) ?>">
                                </td>

                                <td><?= htmlspecialchars($p["name"]) ?></td>

                                <td><?= htmlspecialchars($p["category"]) ?></td>

                                <td>$<?= number_format($p["price"], 2) ?></td>

                                <td>
                                    <form method="post" class="stock-form">
                                        <input type="hidden" name="action" value="update_stock">
                                        <input type="hidden" name="item_id" value="<?= $p["id"] ?>">
                                        <input type="number" class="stock-input" name="stock" value="<?= $p["stock"] ?>" min="0">
                                        <button type="submit" class="btn primary"><i data-lucide="save"></i></button>
                                    </form>
                                </td>

                                <td>
                                    <div class="actions">
                                        <a href="/thakran-electronics/product.php?id=<?= $p["id"] ?>" target="_blank" class="btn view">
                                            <i data-lucide="eye"></i> View
                                        </a>
                                        <a href="/thakran-electronics/admin/edit_product.php?id=<?= $p["id"] ?>" class="btn edit">
                                            <i data-lucide="edit"></i> Edit
                                        </a>
                                        <a href="/thakran-electronics/admin/delete_product.php?id=<?= $p["id"] ?>"
                                           onclick="return confirm('Are you sure you want to delete this product?')"
                                           class="btn delete">
                                           <i data-lucide="trash-2"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i data-lucide="package-x"></i>
                                    <p>No products found</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a class="pager <?= $i == $page ? "active" : "" ?>"
                       href="?page=<?= $i . ($category ? '&category=' . urlencode($category) : '') ?>">
                       <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<script>
    lucide.createIcons();
</script>

<?php include __DIR__ . "/../includes/footer.php"; ?>
<?php ob_end_flush(); ?>