<?php
session_start();
require_once __DIR__ . "/config/db.php";
$page_title = "Product - Thakran Electronics";

$product_id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
if ($product_id <= 0) {
    header("Location: index.php");
    exit();
}

$stmt = $conn->prepare("
    SELECT id, name, description, price, image_url, category, stock
    FROM products 
    WHERE id = ? LIMIT 1
");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$res = $stmt->get_result();

if (!$res || $res->num_rows === 0) {
    include __DIR__ . "/includes/header.php";
    echo '
    <main>
        <section class="container">
            <div class="product-not-found">Product not found!</div>
        </section>
    </main>

    <style>
        .product-not-found {
            font-family: "Instrument Serif", serif;
            font-size: 2rem;
            font-weight: bold;
            letter-spacing: 0.8px;
            text-align: center;
            margin-top: 20vh;
            color: var(--text-primary);
        }
    </style>
    ';
    include __DIR__ . "/includes/footer.php";
    exit();
}

$product = $res->fetch_assoc();
$stmt->close();

include __DIR__ . "/includes/header.php";
?>

<style>
    .product-page {
        margin-top: 2rem;
        margin-bottom: 3rem;
        padding: 0 0.5rem;
        font-family: "Inter", sans-serif;
    }

    .product-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2.2rem;
    }

    @media(max-width: 900px) {
        .product-grid {
            grid-template-columns: 1fr;
        }
    }

    .product-image-box {
        background: #ffffff;
        padding: 1.5rem;
        border-radius: 14px;
        border: 1px solid #ddd;
    }

    .product-image-box img {
        width: 100%;
        height: 420px;
        object-fit: contain;
    }

    .product-summary {
        background: var(--bg-secondary);
        padding: 2rem;
        border-radius: 14px;
        border: 1px solid var(--border-color);
    }

    .product-title {
        font-family: "Instrument Serif", serif;
        font-size: 2rem;
        margin-bottom: .6rem;
    }

    .product-price {
        font-size: 1.9rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .add-btn {
        display: inline-block;
        margin-top: 1rem;
        padding: 0.9rem 1.6rem;
        background: #0080ff;
        color: #fff;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        text-decoration: none;
        transition: .25s;
    }

    .add-btn:hover {
        background: #006fdfff;
    }

    .product-desc {
        margin-top: 1.2rem;
        font-size: 1rem;
        line-height: 1.55rem;
        color: #444;
    }

    .spec-box {
        margin-top: 2rem;
        padding: 1.4rem 1.6rem;
        border-radius: 14px;
        background: #ffffff;
        border: 1px solid #ccc;
    }

    .spec-box h3 {
        margin-bottom: 1rem;
    }

    .spec-table {
        width: 100%;
    }

    .spec-table td {
        padding: .6rem 0;
        font-size: .95rem;
    }

    .spec-table td:first-child {
        width: 140px;
        color: #555;
        font-weight: 600;
    }
</style>

<main>
    <section class="container">
        <div class="product-page">
            <div class="product-grid">
                <div class="product-image-box">
                    <img src="/thakran-electronics/assets/images/products/<?= htmlspecialchars(
                        $product["image_url"]
                    ) ?>">
                </div>

                <div class="product-summary">

                    <h1 class="product-title"><?= htmlspecialchars(
                        $product["name"]
                    ) ?></h1>

                    <div class="product-price">$<?= number_format(
                        $product["price"],
                        2
                    ) ?></div>

                    <a class="add-btn" href="/thakran-electronics/cart/add.php?id=<?= $product[
                        "id"
                    ] ?>">Add to Cart</a>

                    <p class="product-desc"><?= nl2br(
                        htmlspecialchars($product["description"])
                    ) ?></p>

                    <div class="spec-box">
                        <h3>Specifications</h3>
                        <table class="spec-table">
                            <tr>
                                <td>Category</td>
                                <td><?= htmlspecialchars(
                                    $product["category"]
                                ) ?></td>
                            </tr>
                            <tr>
                                <td>Stock</td>
                                <td><?= (int) $product["stock"] ?></td>
                            </tr>
                            <tr>
                                <td>Product ID</td>
                                <td><?= (int) $product["id"] ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . "/includes/footer.php"; ?>
