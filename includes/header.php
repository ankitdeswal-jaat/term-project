<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/db.php";

$cart_count = 0;

if (isset($_SESSION["user_id"])) {
    $cart_sql = "
        SELECT COALESCE(SUM(ci.quantity), 0) AS total_items
        FROM cart c
        LEFT JOIN cart_items ci ON ci.cart_id = c.cart_id
        WHERE c.user_id = ?
        LIMIT 1
    ";

    $stmt = $conn->prepare($cart_sql);
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    $cart_count = (int) ($result["total_items"] ?? 0);

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title)
        ? $page_title
        : "Thakran Electronics" ?></title>

    <link rel="stylesheet" href="/thakran-electronics/css/style.css">
    <link rel="shortcut icon" href="/thakran-electronics/assets/images/icons/favicon.png" type="image/x-icon">

    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        .container {
            max-width: 1500px;
            margin: 0 auto;
            padding: 0 1.25rem;
        }

        header {
            position: sticky;
            top: 0;
            z-index: 10000;
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
        }

        nav {
            padding: 1rem 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo h1 {
            font-family: var(--font-instrument);
            margin: 0;
            font-size: 2rem;
        }

        .logo a {
            text-decoration: none;
            color: var(--text-primary);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            font-family: var(--font-inter);
            z-index: 1000;
        }

        .nav-links.desktop {
            display: flex;
        }

        .nav-links.mobile {
            display: none;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            padding: 7px 14px;
            border-radius: 6px;
            text-decoration: none;
            color: var(--text-primary);
            transition: 0.2s;
        }

        .nav-item:hover {
            background: var(--bg-hover);
        }

        .nav-item svg {
            width: 18px;
            height: 18px;
        }

        .badge-cart {
            color: #0a0a0a;
            font-size: 17px;
            line-height: 1;
            min-width: 20px;
            text-align: center;
            display: inline-block;
        }

        .profile-details {
            position: relative;
        }

        .profile-details summary {
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .profile-menu {
            position: absolute;
            right: 0;
            top: 40px;
            width: 150px;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            display: none;
            flex-direction: column;
            z-index: 1002;
        }

        .profile-details[open] .profile-menu {
            display: flex;
        }

        .profile-menu a {
            padding: 10px 14px;
            border-bottom: 1px solid var(--border-color);
            text-decoration: none;
            color: var(--text-primary);
        }

        .profile-menu a:last-child {
            border-bottom: none;
        }

        .logout {
            color: #c20000;
            font-weight: bold;
        }

        #hamburger {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
        }

        .mobile-header {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.25rem;
            z-index: 10001;
            justify-content: space-between;
            align-items: center;
        }

        .mobile-header h2 {
            font-family: var(--font-instrument);
            margin: 0;
            font-size: 2rem;
            color: var(--text-primary);
        }

        .close-menu {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-primary);
        }

        @media (max-width: 900px) {
            #hamburger {
                display: block;
            }

            .nav-links.desktop {
                display: none;
            }

            .nav-links.mobile {
                display: flex;
                flex-direction: column;
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: var(--bg-primary);
                justify-content: center;
                align-items: center;
                gap: 2rem;
                opacity: 0;
                pointer-events: none;
                transition: 0.3s;
            }

            .nav-links.mobile.active {
                opacity: 1;
                pointer-events: auto;
            }

            .mobile-header.active {
                display: flex;
                height: 75px;
            }
        }
    </style>
</head>

<body>
    <header>
        <section class="container">
            <nav>

                <div class="logo">
                    <h1><a href="/thakran-electronics/index.php">Thakran Electronics</a></h1>
                </div>

                <button id="hamburger"><i data-lucide="menu"></i></button>

                <div class="nav-links desktop" id="nav-links-desktop">

                    <?php if (!isset($_SESSION["user_id"])): ?>

                        <a class="nav-item" href="/thakran-electronics/login.php">
                            <i data-lucide="log-in"></i> Login
                        </a>

                    <?php else: ?>

                        <?php if (
                            !empty($_SESSION["is_admin"]) &&
                            $_SESSION["is_admin"] == 1
                        ): ?>

                            <a class="nav-item" href="/thakran-electronics/admin/index.php">
                                <i data-lucide="shield-check"></i> Admin Panel
                            </a>

                            <details class="profile-details">
                                <summary class="nav-item">
                                    <i data-lucide="user"></i> Profile
                                </summary>
                                <div class="profile-menu">
                                    <a href="/thakran-electronics/user/profile.php">View Profile</a>
                                    <a class="logout" href="/thakran-electronics/logout.php">Logout</a>
                                </div>
                            </details>

                        <?php else: ?>

                            <a class="nav-item" href="/thakran-electronics/index.php"><i data-lucide="home"></i> Home</a>
                            <a class="nav-item" href="/thakran-electronics/products.php"><i data-lucide="package"></i> Products</a>
                            <a class="nav-item" href="/thakran-electronics/cart/index.php">
                                <i data-lucide="shopping-cart"></i> Cart
                                <span class="badge-cart">(<?= $cart_count ?>)</span>
                            </a>
                            <a class="nav-item" href="/thakran-electronics/user/order.php"><i data-lucide="receipt"></i> Orders</a>

                            <details class="profile-details">
                                <summary class="nav-item"><i data-lucide="user"></i> Profile</summary>
                                <div class="profile-menu">
                                    <a href="/thakran-electronics/user/profile.php">View Profile</a>
                                    <a class="logout" href="/thakran-electronics/logout.php">Logout</a>
                                </div>
                            </details>

                        <?php endif; ?>

                    <?php endif; ?>

                </div>
                
                <div class="nav-links mobile" id="nav-links-mobile">
                    <?php if (!isset($_SESSION["user_id"])): ?>

                        <a class="nav-item" href="/thakran-electronics/login.php"><i data-lucide="log-in"></i> Login</a>

                    <?php else: ?>

                        <?php if (
                            !empty($_SESSION["is_admin"]) &&
                            $_SESSION["is_admin"] == 1
                        ): ?>

                            <a class="nav-item" href="/thakran-electronics/admin/index.php"><i data-lucide="shield-check"></i> Admin Panel</a>
                            <a class="nav-item" href="/thakran-electronics/user/profile.php"><i data-lucide="user"></i> Profile</a>
                            <a class="nav-item logout" href="/thakran-electronics/logout.php"><i data-lucide="log-out"></i> Logout</a>

                        <?php else: ?>

                            <a class="nav-item" href="/thakran-electronics/index.php"><i data-lucide="home"></i> Home</a>
                            <a class="nav-item" href="/thakran-electronics/products.php"><i data-lucide="package"></i> Products</a>
                            <a class="nav-item" href="/thakran-electronics/cart/index.php">
                                <i data-lucide="shopping-cart"></i> Cart
                                <span class="badge-cart">(<?= $cart_count ?>)</span>
                            </a>
                            <a class="nav-item" href="/thakran-electronics/user/order.php"><i data-lucide="receipt"></i> Orders</a>
                            <a class="nav-item" href="/thakran-electronics/user/profile.php"><i data-lucide="user"></i> Profile</a>
                            <a class="nav-item logout" href="/thakran-electronics/logout.php"><i data-lucide="log-out"></i> Logout</a>

                        <?php endif; ?>

                    <?php endif; ?>
                </div>

                <div class="mobile-header" id="mobile-header">
                    <h2>Thakran Electronics</h2>
                    <button class="close-menu" id="close-menu"><i data-lucide="x"></i></button>
                </div>

            </nav>
        </section>
    </header>

    <script>
        lucide.createIcons();

        const mobileMenu = document.getElementById("nav-links-mobile");
        const mobileHeader = document.getElementById("mobile-header");

        document.getElementById("hamburger").addEventListener("click", () => {
            mobileMenu.classList.add("active");
            mobileHeader.classList.add("active");
        });

        document.getElementById("close-menu").addEventListener("click", () => {
            mobileMenu.classList.remove("active");
            mobileHeader.classList.remove("active");
        });
    </script>