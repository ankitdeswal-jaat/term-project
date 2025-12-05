<?php
ob_start();
session_start();
if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] != 1) {
    header("Location: /thakran-electronics/login.php");
    exit();
}

require_once __DIR__ . "/../config/db.php";
$page_title = "Admin User - Thakran Electronics";
include __DIR__ . "/../includes/header.php";

$users = $conn->query(
    "SELECT user_id, name, email, is_admin FROM users ORDER BY user_id DESC"
);
?>

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

    .table-wrapper {
        background: var(--bg-secondary);
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        box-shadow: var(--shadow);
    }

    .admin-table {
        width: 100%;
        min-width: 700px;
        border-collapse: collapse;
        font-family: var(--font-inter);
    }

    .admin-table thead {
        background: var(--bg-primary);
    }

    .admin-table th:nth-child(1) {
        width: 90px;
    }

    .admin-table th:nth-child(2) {
        width: 220px;
    }

    .admin-table th:nth-child(3) {
        width: 300px;
    }

    .admin-table th:nth-child(4) {
        width: 120px;
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
        vertical-align: middle;
        white-space: nowrap;
    }

    .admin-table tbody tr:hover {
        background: rgba(0, 0, 0, 0.02);
    }

    .badge {
        padding: .45rem .75rem;
        border-radius: 10px;
        font-size: .8rem;
        font-weight: 600;
    }

    .badge-admin {
        background: #e5f0ff;
        color: #0052cc;
    }

    .badge-user {
        background: #e0ffe5;
        color: #008a2e;
    }

    @media (max-width: 768px) {
        .table-wrapper {
            border-radius: 8px;
        }

        .admin-table {
            font-size: 0.85rem;
            min-width: 600px;
        }

        .admin-table th,
        .admin-table td {
            padding: 0.75rem;
        }

        .page-title {
            font-size: 1.6rem;
        }
    }
</style>

<section>
    <div class="admin-container">

        <div class="page-header">
            <h2 class="page-title">Manage Users</h2>
        </div>

        <div class="table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($u = $users->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= $u["user_id"] ?></td>
                            <td><?= htmlspecialchars($u["name"]) ?></td>
                            <td><?= htmlspecialchars($u["email"]) ?></td>
                            <td>
                                <?php if ($u["is_admin"] == 1): ?>
                                    <span class="badge badge-admin">Admin</span>
                                <?php else: ?>
                                    <span class="badge badge-user">Customer</span>
                                <?php endif; ?>
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
