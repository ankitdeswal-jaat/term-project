<?php
session_start();
require_once __DIR__ . "/../config/db.php";
$page_title = "Order Placed - Thakran Electronics";
include __DIR__ . "/../includes/header.php";
?>

<style>
    main {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .success-container {
        max-width: 520px;
        margin: 5rem auto;
        background: #f8f9fb;
        padding: 2.5rem;
        border-radius: 16px;
        border: 1px solid #e3e3e3;
        text-align: center;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
        font-family: "Inter", sans-serif;
    }

    .success-icon {
        width: 70px;
        height: 70px;
        background: #d6ffe2;
        color: #0a8c2a;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 40px;
        font-weight: bold;
    }

    .success-container h2 {
        font-size: 2rem;
        margin-bottom: 0.7rem;
        font-family: "Instrument Serif", serif;
    }

    .success-container p {
        font-size: 1rem;
        color: #444;
        margin-bottom: 1.7rem;
        line-height: 1.6;
    }

    .success-btns {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 1.4rem;
        flex-wrap: wrap;
    }

    .btn-primary {
        padding: 12px 18px;
        background: #0056b3;
        color: #fff;
        border-radius: 10px;
        font-size: 0.95rem;
        text-decoration: none;
        font-weight: 600;
    }

    .btn-primary:hover {
        background: #004a99;
    }

    .btn-outline {
        padding: 12px 18px;
        background: #e9f3ff;
        color: #0056b3;
        border: 1px solid #aad4ff;
        border-radius: 10px;
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 600;
    }

    .btn-outline:hover {
        background: #d9ebff;
    }
</style>

<main>
    <div class="success-container">

        <div class="success-icon">✔</div>

        <h2>Order Placed Successfully</h2>

        <p>
            Thank you! Your order has been placed successfully.<br>
            You can track and manage your order anytime.
        </p>

        <div class="success-btns">
            <a class="btn-primary" href="/thakran-electronics/products.php">Continue Shopping</a>
            <a class="btn-outline" href="/thakran-electronics/user/order.php">View My Orders</a>
        </div>

    </div>
</main>

<?php include __DIR__ . "/../includes/footer.php"; ?>
