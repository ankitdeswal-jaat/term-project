<?php
session_start();

if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] != 1) {
    header("Location: /thakran-electronics/login.php");
    exit();
}

require_once __DIR__ . "/../config/db.php";

$order_id = (int) ($_GET["id"] ?? 0);
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["status"])) {
        $new_status = $_POST["status"];

        $stmt = $conn->prepare(
            "UPDATE orders SET status = ? WHERE order_id = ?"
        );
        $stmt->bind_param("si", $new_status, $order_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: order_view.php?id=" . $order_id);
    exit();
}

$stmt = $conn->prepare("
    SELECT o.*, u.name, u.email
    FROM orders o 
    JOIN users u ON o.user_id = u.user_id
    WHERE o.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    die("<h2>Order not found.</h2>");
}

$stmt = $conn->prepare("
    SELECT oi.*, p.name, p.category, p.description, p.image_url, p.price
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result();
$stmt->close();

$statuses = ["Pending", "Processing", "Shipped", "Completed", "Cancelled"];

$page_title = "Order #" . $order["order_id"] . " - Thakran Electronics";
include __DIR__ . "/../includes/header.php";
?>

<style>
    .admin-container {
        max-width: 1500px;
        margin: 2rem auto;
        padding: 0 1.25rem;
        overflow: visible;
    }

    .admin-header {
        margin-bottom: 1.5rem;
    }

    .admin-title {
        font-family: var(--font-instrument);
        font-size: 1.8rem;
        margin-bottom: 0.3rem;
        color: var(--text-primary);
        font-weight: 700;
    }

    .admin-subtitle {
        font-family: var(--font-inter);
        font-size: 0.9rem;
        color: var(--text-secondary);
        font-weight: 400;
    }

    .order-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        margin-bottom: 1.25rem;
    }

    .card {
        background: var(--bg-secondary);
        padding: 1.5rem;
        border-radius: 14px;
        border: 1px solid var(--border-color);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
        font-family: var(--font-inter);
        display: flex;
        flex-direction: column;
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .card-icon {
        width: 36px;
        height: 36px;
        background: var(--bg-primary);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border-color);
    }

    .card h3 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.04);
        font-size: 0.9rem;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: var(--text-secondary);
        font-weight: 500;
    }

    .info-value {
        color: var(--text-primary);
        font-weight: 600;
        text-align: right;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 0.75rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .Pending {
        background: #fff4d9;
        color: #b78100;
    }

    .Processing {
        background: #dfeaff;
        color: #0052cc;
    }

    .Shipped {
        background: #e6f7ff;
        color: #0088c7;
    }

    .Completed {
        background: #d9ffe4;
        color: #009e39;
    }

    .Cancelled {
        background: #ffe1e1;
        color: #b40000;
    }

    .form-group {
        margin-top: auto;
        padding-top: 1rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .status-dropdown {
        width: 100%;
        padding: 0.65rem 0.75rem;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: var(--bg-primary);
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
    }

    .status-dropdown:focus {
        outline: none;
        border-color: var(--button-color);
    }

    .btn {
        width: 100%;
        padding: 0.65rem 1rem;
        border-radius: 8px;
        border: none;
        background: var(--button-color);
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: 0.2s;
    }

    .btn:hover {
        background: #004494;
    }

    .products-card {
        grid-column: 1 / -1;
    }

    .products-list {
        overflow-y: auto;
        max-height: 500px;
        padding-right: 0.5rem;
    }

    .product-item {
        display: grid;
        grid-template-columns: 80px 1fr auto;
        gap: 1rem;
        padding: 1rem;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        margin-bottom: 0.75rem;
        background: var(--bg-primary);
        align-items: center;
    }

    .product-img {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        object-fit: contain;
        background: white;
        border: 1px solid var(--border-color);
        padding: 0.4rem;
    }

    .product-info h4 {
        font-size: 1rem;
        margin: 0 0 0.25rem 0;
        font-weight: 600;
    }

    .product-category {
        font-size: 0.8rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .product-desc {
        margin-top: 0.4rem;
        font-size: 0.85rem;
        color: var(--text-secondary);
        line-height: 1.3;
        max-width: 90%;
    }

    .product-pricing {
        text-align: right;
    }

    .qty-badge {
        background: var(--bg-secondary);
        padding: 0.3rem 0.6rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .price {
        font-size: 0.85rem;
        margin: 0.35rem 0;
        color: var(--text-secondary);
    }

    .subtotal {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    @media (max-width: 900px) {
        .order-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {

        .product-item {
            grid-template-columns: 70px 1fr;
            grid-template-rows: auto auto;
            gap: 0.75rem;
        }

        .product-pricing {
            grid-column: 1 / -1;
            display: flex;
            justify-content: space-between;
            margin-top: 0.5rem;
        }

        .product-img {
            width: 70px;
            height: 70px;
        }

        .product-info h4 {
            font-size: 0.95rem;
        }

        .product-desc {
            font-size: 0.8rem;
            max-width: 100%;
        }

        .admin-title {
            font-size: 1.5rem;
        }
    }
</style>

<section>
    <div class="admin-container">

        <div class="admin-header">
            <h2 class="admin-title">Order #<?= $order["order_id"] ?></h2>
            <p class="admin-subtitle">Order placed on <?= date(
                "F j, Y",
                strtotime($order["order_date"])
            ) ?></p>
        </div>

        <div class="order-grid">

            <div class="card">
                <div class="card-header">
                    <div class="card-icon"><i data-lucide="package"></i></div>
                    <h3>Order Details</h3>
                </div>

                <div class="info-row">
                    <span class="info-label">Order ID</span>
                    <span class="info-value">#<?= $order["order_id"] ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Order Date</span>
                    <span class="info-value"><?= date(
                        "M d, Y",
                        strtotime($order["order_date"])
                    ) ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Total Amount</span>
                    <span class="info-value">$<?= number_format(
                        $order["total_price"],
                        2
                    ) ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="badge <?= $order["status"] ?>"><?= $order[
    "status"
] ?></span>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-icon"><i data-lucide="user"></i></div>
                    <h3>Customer Info</h3>
                </div>

                <div class="info-row">
                    <span class="info-label">Name</span>
                    <span class="info-value"><?= htmlspecialchars(
                        $order["name"]
                    ) ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value"><?= htmlspecialchars(
                        $order["email"]
                    ) ?></span>
                </div>

                <form method="post" class="form-group">
                    <label class="form-label">Update Status</label>
                    <select name="status" class="status-dropdown">
                        <?php foreach ($statuses as $s): ?>
                            <option value="<?= $s ?>" <?= $s == $order["status"]
    ? "selected"
    : "" ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>

                    <button class="btn">
                        <i data-lucide="check"></i> Update Status
                    </button>
                </form>
            </div>

            <div class="card products-card">
                <div class="card-header">
                    <div class="card-icon"><i data-lucide="shopping-bag"></i></div>
                    <h3>Products Ordered</h3>
                </div>

                <div class="products-list">
                    <?php while ($i = $items->fetch_assoc()): ?>
                        <div class="product-item">

                            <img src="/thakran-electronics/assets/images/products/<?= $i[
                                "image_url"
                            ] ?>"
                                class="product-img">

                            <div class="product-info">
                                <h4><?= htmlspecialchars($i["name"]) ?></h4>
                                <span class="product-category"><?= htmlspecialchars(
                                    $i["category"]
                                ) ?></span>
                                <p class="product-desc">
                                    <?= substr(
                                        htmlspecialchars($i["description"]),
                                        0,
                                        100
                                    ) ?>...
                                </p>
                            </div>

                            <div class="product-pricing">
                                <span class="qty-badge">×<?= $i[
                                    "quantity"
                                ] ?></span>
                                <div class="price">$<?= number_format(
                                    $i["price"],
                                    2
                                ) ?> each</div>
                                <div class="subtotal">$<?= number_format(
                                    $i["quantity"] * $i["price"],
                                    2
                                ) ?></div>
                            </div>

                        </div>
                    <?php endwhile; ?>
                </div>

            </div>

        </div>

    </div>
</section>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>

<?php include __DIR__ . "/../includes/footer.php"; ?>
