<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: /thakran-electronics/login.php");
    exit();
}

require_once __DIR__ . "/../config/db.php";
$page_title = "Order - Thakran Electronics";
include __DIR__ . "/../includes/header.php";

$user_id = (int) $_SESSION["user_id"];

$sql = "SELECT order_id, total_price, order_date, status
        FROM orders
        WHERE user_id = ?
        ORDER BY order_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
?>

<script src="https://unpkg.com/lucide@latest"></script>

<style>
    .orders-container {
        max-width: 1400px;
        margin: 2.5rem auto;
        padding: 0 1.5rem;
        font-family: "Inter", sans-serif;
    }

    .orders-title {
        font-size: 2.4rem;
        font-family: "Instrument Serif", serif;
        margin-bottom: 2rem;
        color: #1a1a1a;
        letter-spacing: -0.5px;
    }

    .orders-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.8rem;
    }

    .order-card {
        background: #f5f5f5;
        border-radius: 16px;
        border: 1px solid #e0e0e0;
        padding: 1.8rem;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
        transition: .25s ease;
    }

    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 26px rgba(0, 0, 0, 0.11);
    }

    .delivered-box {
        padding: .6rem 1rem;
        background: #d1fae5;
        color: #0c7d28;
        border-radius: 8px;
        font-weight: 600;
        margin-bottom: 1rem;
        text-align: center;
        font-size: .9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .order-id {
        font-family: var(--font-instrument);
        letter-spacing: 1.5px;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #111;
    }

    .order-line {
        font-size: 1rem;
        color: #1b1b1b;
        margin-bottom: .8rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .badge {
        padding: .45rem .9rem;
        border-radius: 8px;
        font-size: .85rem;
        font-weight: 600;
        display: inline-block;
        margin-top: .7rem;
    }

    .Pending {
        background: #fff7d6;
        color: #b78a00;
    }

    .Processing {
        background: #e1ecff;
        color: #0052cc;
    }

    .Shipped {
        background: #e6f7ff;
        color: #0284c7;
    }

    .Completed {
        background: #d1fae5;
        color: #047857;
    }

    .Cancelled {
        background: #ffe1e1;
        color: #b30000;
    }

    .product-mini {
        display: flex;
        gap: 15px;
        margin-top: 1rem;
        background: #fff;
        border: 1px solid #ddd;
        padding: 1rem;
        border-radius: 14px;
    }

    .mini-img {
        width: 70px;
        height: 70px;
        object-fit: contain;
        border-radius: 10px;
        border: 1px solid #d1d1d1;
        padding: 5px;
        background: #fff;
    }

    .mini-info {
        font-size: .9rem;
        line-height: 1.35rem;
    }

    .mini-title {
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 180px;
        margin-bottom: 3px;
    }

    .empty-msg {
        margin-top: 3rem;
        text-align: center;
        font-size: 1.2rem;
        color: #555;
    }
</style>

<main>
    <section class="container">
        <div class="orders-container">
            <h2 class="orders-title">My Orders</h2>

            <?php if ($res->num_rows > 0): ?>
                <div class="orders-grid">

                    <?php while ($o = $res->fetch_assoc()):

                        $oid = $o["order_id"];

                        $items = $conn->query("
                            SELECT p.name, p.image_url, oi.quantity, oi.price
                            FROM order_items oi
                            JOIN products p ON oi.product_id = p.id
                            WHERE oi.order_id = $oid
                        ");

                        $item_names = [];
                        $name_res = $conn->query("
                            SELECT p.name
                            FROM order_items oi
                            JOIN products p ON oi.product_id = p.id
                            WHERE oi.order_id = $oid
                        ");
                        while ($row = $name_res->fetch_assoc()) {
                            $item_names[] = $row["name"];
                        }
                        ?>

                        <div class="order-card">

                            <?php if ($o["status"] === "Completed"): ?>
                                <div class="delivered-box">
                                    <i data-lucide="check-circle"></i> Delivered Successfully
                                </div>
                            <?php endif; ?>

                            <div class="order-id">Order #<?= $oid ?></div>

                            <?php if (!empty($item_names)): ?>
                                <div class="order-line">
                                    <i data-lucide="package"></i>
                                    <strong>Items:</strong> <?= implode(
                                        ", ",
                                        $item_names
                                    ) ?>
                                </div>
                            <?php endif; ?>

                            <div class="order-line">
                                <i data-lucide="calendar"></i> <?= $o[
                                    "order_date"
                                ] ?>
                            </div>

                            <div class="order-line">
                                <i data-lucide="badge-dollar-sign"></i>
                                Total: $<?= number_format(
                                    $o["total_price"],
                                    2
                                ) ?>
                            </div>

                            <span class="badge <?= $o["status"] ?>"><?= $o[
    "status"
] ?></span>

                            <?php while ($p = $items->fetch_assoc()): ?>
                                <div class="product-mini">
                                    <img class="mini-img"
                                        src="/thakran-electronics/assets/images/products/<?= htmlspecialchars(
                                            $p["image_url"]
                                        ) ?>">
                                    <div class="mini-info">
                                        <span class="mini-title"><?= htmlspecialchars(
                                            $p["name"]
                                        ) ?></span>
                                        Qty: <?= $p["quantity"] ?><br>
                                        Price: $<?= number_format(
                                            $p["price"],
                                            2
                                        ) ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>

                        </div>

                    <?php
                    endwhile; ?>

                </div>

            <?php else: ?>
                <p class="empty-msg">You have not placed any orders yet.</p>
            <?php endif; ?>

        </div>
    </section>
</main>

<script>
    lucide.createIcons();
</script>