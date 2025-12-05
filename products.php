<?php
session_start();
require_once __DIR__ . "/config/db.php";
$page_title = "Products - Thakran Electronics";
include __DIR__ . "/includes/header.php";

$search = trim($_GET["search"] ?? "");
$category = trim($_GET["category"] ?? "");
$sort = trim($_GET["sort"] ?? "");

$sql = "SELECT id, name, price, image_url, category FROM products WHERE 1";
$params = [];
$types = "";

if ($search !== "") {
    $sql .= " AND name LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}

if ($category !== "") {
    $sql .= " AND category = ?";
    $params[] = $category;
    $types .= "s";
}

switch ($sort) {
    case "low-high":
        $sql .= " ORDER BY price ASC";
        break;
    case "high-low":
        $sql .= " ORDER BY price DESC";
        break;
    default:
        $sql .= " ORDER BY id DESC";
}

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$res = $stmt->get_result();

$categories = [
    "Laptop",
    "Mouse",
    "Keyboard",
    "Monitor",
    "Graphics Card",
    "Memory",
    "Storage",
];
?>

<style>
    .filter-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        margin: 2rem 0rem;
    }

    .filter-bar h2 {
        font-family: "Instrument Serif", serif;
        font-size: 2rem;
        margin: 0;
    }

    .filters-right {
        display: flex;
        gap: .8rem;
        flex-wrap: wrap;
    }

    .filter-input,
    .filter-select,
    .reset-btn {
        padding: 8px 12px;
        font-family: "Inter", sans-serif;
        border: 1px solid var(--border-color);
        background: var(--bg-secondary);
        font-size: .95rem;
        border-radius: 6px;
    }

    .reset-btn {
        background: #ececec;
        cursor: pointer;
    }

    .reset-btn:hover {
        background: #d8d8d8;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.6rem;
        margin-bottom: 4rem;
    }

    @media (max-width: 1200px) {
        .products-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 900px) {
        .products-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 550px) {
        .products-grid {
            grid-template-columns: 1fr;
        }
    }

    .prod-card {
        font-family: "Inter", sans-serif;
        background: var(--bg-secondary);
        border-radius: 10px;
        border: 1px solid var(--border-color);
        padding: 1rem;
        display: flex;
        flex-direction: column;
        box-shadow: var(--shadow);
        transition: .25s ease;
    }

    .prod-card:hover {
        transform: translateY(-5px);
    }

    .prod-img {
        width: 100%;
        height: 210px;
        object-fit: contain;
        background: #fff;
        border-radius: 8px;
        border: 1px solid #eee;
        padding: 10px;
    }

    .prod-card h3 {
        color: var(--text-primary);
        margin: .9rem 0 .9rem;
        font-size: 1.3rem;
        font-weight: 600;
        line-height: 1.35rem;
    }

    .prod-price {
        font-family: var(--font-instrument);
        letter-spacing: 1.5px;
        font-size: 1.6rem;
        font-weight: bold;
        margin: 0 0 1rem 0;
    }

    .btn-row {
        display: flex;
        gap: .6rem;
        margin-top: auto;
    }

    .btn {
        flex: 1;
        padding: .65rem;
        border-radius: 8px;
        text-align: center;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid var(--border-color);
    }

    .view {
        background: #ececec;
        color: #222;
    }

    .view:hover {
        background: #d5d5d5;
    }

    .cart {
        background: #0080ff;
        color: #fff;
    }

    .cart:hover {
        background: #0066cc;
    }

    .empty-box {
        font-family: var(--font-instrument);
        background: var(--bg-secondary);
        padding: 4rem;
        text-align: center;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        font-size: 2rem;
        color: var(--text-secondary);
        margin-top: 2rem;
    }
</style>

<main>
    <section class="container">
        <div class="filter-bar">
            <h2>All Products</h2>
            <form method="GET" class="filters-right" id="filterForm">
                <input type="text" name="search" class="filter-input"
                    placeholder="Search..."
                    value="<?= htmlspecialchars($search) ?>">

                <select name="sort" class="filter-select" onchange="this.form.submit()">
                    <option value="">Sort</option>
                    <option value="low-high" <?= $sort == "low-high"
                        ? "selected"
                        : "" ?>>Low → High</option>
                    <option value="high-low" <?= $sort == "high-low"
                        ? "selected"
                        : "" ?>>High → Low</option>
                </select>

                <select name="category" class="filter-select" onchange="this.form.submit()">
                    <option value="">Category</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c ?>" <?= $c == $category
    ? "selected"
    : "" ?>>
                            <?= $c ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="button" onclick="window.location='products.php'" class="reset-btn">Reset</button>
            </form>
        </div>

        <?php if ($res->num_rows > 0): ?>
            <div class="products-grid">
                <?php while ($p = $res->fetch_assoc()): ?>
                    <div class="prod-card">

                        <img class="prod-img"
                            src="/thakran-electronics/assets/images/products/<?= htmlspecialchars(
                                $p["image_url"]
                            ) ?>"
                            alt="<?= htmlspecialchars($p["name"]) ?>">

                        <h3><?= htmlspecialchars($p["name"]) ?></h3>

                        <p class="prod-price">$<?= number_format(
                            $p["price"],
                            2
                        ) ?></p>

                        <div class="btn-row">
                            <a class="btn view" href="/thakran-electronics/product.php?id=<?= $p[
                                "id"
                            ] ?>">View</a>
                            <a class="btn cart" href="/thakran-electronics/cart/add.php?id=<?= $p[
                                "id"
                            ] ?>">Add to Cart</a>
                        </div>

                    </div>
                <?php endwhile; ?>
            </div>

        <?php else: ?>
            <div class="empty-box">
                No products found. Try searching or filtering again.
            </div>
        <?php endif; ?>
    </section>
</main>

<?php include __DIR__ . "/includes/footer.php"; ?>
