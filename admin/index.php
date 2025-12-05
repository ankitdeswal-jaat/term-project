<?php
session_start();
if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] != 1) {
    header("Location: /thakran-electronics/login.php");
    exit();
}

require_once __DIR__ . "/../config/db.php";
$page_title = "Admin Dashboard - Thakran Electronics";
include __DIR__ . "/../includes/header.php";
?>

<style>
    .admin-container {
        max-width: 1500px;
        margin: 3rem auto;
        padding: 0 1.25rem;
    }

    .admin-header {
        margin-bottom: 3rem;
    }

    .admin-title {
        font-family: var(--font-instrument);
        font-size: 2.4rem;
        margin-bottom: 2rem;
        color: var(--text-primary);
        font-weight: 700;
    }

    .admin-subtitle {
        font-family: var(--font-inter);
        font-size: 1.1rem;
        color: var(--text-secondary);
        font-weight: 400;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .stat-card {
        font-family: var(--font-inter);
        background: var(--bg-primary);
        padding: 1.6rem 1.8rem;
        border-radius: 14px;
        border: 1px solid var(--border-color);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
        transition: 0.25s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 16px rgba(0, 0, 0, 0.12);
    }

    .stat-card h4 {
        font-size: 1rem;
        color: var(--text-secondary);
        margin-bottom: .5rem;
        font-weight: 500;
    }

    .stat-card span {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1;
    }

    .tile-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.7rem;
    }

    .tile {
        text-decoration: none;
        background: var(--bg-secondary);
        padding: 2rem;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        transition: 0.25s ease;
        display: block;
    }

    .tile:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.13);
    }

    .tile-icon {
        width: 55px;
        height: 55px;
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
    }

    .tile-icon svg {
        width: 26px;
        height: 26px;
    }

    .tile h3 {
        font-size: 1.4rem;
        font-family: var(--font-instrument);
        margin-bottom: .3rem;
        font-weight: 600;
    }

    .tile p {
        font-size: .95rem;
        font-family: var(--font-inter);
        color: var(--text-secondary);
        line-height: 1.45;
    }
</style>

<section>
    <div class="admin-container">

        <div class="admin-header">
            <h1 class="admin-title">Admin Dashboard</h1>
            <p class="admin-subtitle">Welcome back! Here's what's happening with your store today.</p>
        </div>

        <div class="stats-grid">

            <div class="stat-card">
                <h4>Total Products</h4>
                <span><?= $conn
                    ->query("SELECT COUNT(*) FROM products")
                    ->fetch_row()[0] ?></span>
            </div>

            <div class="stat-card">
                <h4>Total Orders</h4>
                <span><?= $conn
                    ->query("SELECT COUNT(*) FROM orders")
                    ->fetch_row()[0] ?></span>
            </div>

            <div class="stat-card">
                <h4>Total Users</h4>
                <span><?= $conn
                    ->query("SELECT COUNT(*) FROM users")
                    ->fetch_row()[0] ?></span>
            </div>

            <div class="stat-card">
                <h4>Pending Orders</h4>
                <span><?= $conn
                    ->query(
                        "SELECT COUNT(*) FROM orders WHERE status='pending'"
                    )
                    ->fetch_row()[0] ?></span>
            </div>

        </div>

        <div class="tile-grid">

            <a class="tile" href="/thakran-electronics/admin/products.php">
                <div class="tile-icon"><i data-lucide="shopping-cart"></i></div>
                <h3>Products</h3>
                <p>Manage inventory, pricing, images, and stock control.</p>
            </a>

            <a class="tile" href="/thakran-electronics/admin/add_product.php">
                <div class="tile-icon"><i data-lucide="plus"></i></div>
                <h3>Add Product</h3>
                <p>Add new items with descriptions and product photos.</p>
            </a>

            <a class="tile" href="/thakran-electronics/admin/orders.php">
                <div class="tile-icon"><i data-lucide="box"></i></div>
                <h3>Orders</h3>
                <p>View, track, and update customer orders with details.</p>
            </a>

            <a class="tile" href="/thakran-electronics/admin/users.php">
                <div class="tile-icon"><i data-lucide="user"></i></div>
                <h3>Users</h3>
                <p>Manage customer and admin accounts securely.</p>
            </a>

        </div>

    </div>
</section>

<script>
    lucide.createIcons();
</script>

<?php include __DIR__ . "/../includes/footer.php"; ?>
