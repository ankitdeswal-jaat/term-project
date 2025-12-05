<?php
ob_start();
session_start();
if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] != 1) {
    header("Location: /thakran-electronics/login.php");
    exit();
}

require_once __DIR__ . "/../config/db.php";
$page_title = "Admin Orders - Thakran Electronics";
include __DIR__ . "/../includes/header.php";

$res = $conn->query("
    SELECT o.order_id, o.user_id, o.total_price, o.order_date, o.status,
           u.name AS customer_name
    FROM orders o
    JOIN users u ON o.user_id = u.user_id
    ORDER BY o.order_date DESC
");
?>

<style>
    .admin-container {
        max-width: 1500px;
        margin: 2.5rem auto;
        padding: 0 1.25rem;
    }

    .page-title {
        font-family: var(--font-instrument);
        font-size: 2rem;
        color: var(--text-primary);
        margin-bottom: 1.5rem;
    }

    .table-card {
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
        min-width: 950px;
        border-collapse: collapse;
        font-family: var(--font-inter);
    }

    .admin-table th:nth-child(1) {
        width: 120px;
    }

    .admin-table th:nth-child(2) {
        width: 220px;
    }

    .admin-table th:nth-child(3) {
        width: 140px;
    }

    .admin-table th:nth-child(4) {
        width: 180px;
    }

    .admin-table th:nth-child(5) {
        width: 150px;
    }

    .admin-table th:nth-child(6) {
        width: 100px;
    }

    .admin-table thead {
        background: var(--bg-primary);
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
    }

    .admin-table tbody tr:hover {
        background: rgba(0, 0, 0, 0.02);
    }

    .badge {
        padding: .45rem .75rem;
        border-radius: 10px;
        font-size: .78rem;
        font-weight: 600;
        display: inline-block;
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

    .btn {
        padding: 0.55rem 1rem;
        border-radius: var(--radius);
        font-family: var(--font-inter);
        background: var(--button-color);
        color: white;
        font-size: 0.85rem;
        text-decoration: none;
        transition: 0.2s;
        white-space: nowrap;
    }

    .btn:hover {
        background: #004494;
    }

    @media (max-width: 768px) {

        .admin-table {
            min-width: 850px;
            font-size: 0.85rem;
        }

        .admin-table th,
        .admin-table td {
            padding: 0.75rem 0.5rem;
        }

        .page-title {
            font-size: 1.6rem;
        }
    }
</style>

<section>
    <div class="admin-container">

        <h2 class="page-title">All Orders</h2>

        <div class="table-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($o = $res->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= $o["order_id"] ?></td>

                            <td><?= htmlspecialchars(
                                $o["customer_name"]
                            ) ?></td>

                            <td>$<?= number_format($o["total_price"], 2) ?></td>
                            <td><?= $o["order_date"] ?></td>

                            <td>
                                <span class="badge <?= $o["status"] ?>">
                                    <?= $o["status"] ?>
                                </span>
                            </td>

                            <td>
                                <a class="btn" href="/thakran-electronics/admin/order_view.php?id=<?= $o[
                                    "order_id"
                                ] ?>">
                                    View
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>

            </table>
        </div>

    </div>
</section>

<?php include __DIR__ . "/../includes/footer.php"; ?>
<?php ob_end_flush(); ?>
