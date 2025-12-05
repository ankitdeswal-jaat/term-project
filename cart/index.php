<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: /thakran-electronics/login.php");
    exit();
}

require_once __DIR__ . "/../config/db.php";

$user_id = (int) $_SESSION["user_id"];

$addrStmt = $conn->prepare(
    "SELECT * FROM user_addresses WHERE user_id = ? LIMIT 1"
);
$addrStmt->bind_param("i", $user_id);
$addrStmt->execute();
$address = $addrStmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["save_address"])) {
    $fullname = $_POST["full_name"];
    $phone = $_POST["phone"];
    $pincode = $_POST["pincode"];
    $addr1 = $_POST["address_line1"];
    $addr2 = $_POST["address_line2"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $country = $_POST["country"];

    if ($address) {
        $update = $conn->prepare("
            UPDATE user_addresses SET
            full_name=?, phone=?, pincode=?, address_line1=?, address_line2=?, city=?, state=?, country=?
            WHERE user_id=?
        ");

        $update->bind_param(
            "ssssssssi",
            $fullname,
            $phone,
            $pincode,
            $addr1,
            $addr2,
            $city,
            $state,
            $country,
            $user_id
        );
        $update->execute();
    } else {
        $insert = $conn->prepare("
            INSERT INTO user_addresses
            (user_id, full_name, phone, pincode, address_line1, address_line2, city, state, country, is_default)
            VALUES (?,?,?,?,?,?,?,?,?,1)
        ");

        $insert->bind_param(
            "issssssss",
            $user_id,
            $fullname,
            $phone,
            $pincode,
            $addr1,
            $addr2,
            $city,
            $state,
            $country
        );
        $insert->execute();
    }

    $_SESSION["address_saved"] = true;
    header("Location: /thakran-electronics/cart/index.php");
    exit();
}

$page_title = "Cart - Thakran Electronics";
include __DIR__ . "/../includes/header.php";

$cartStmt = $conn->prepare(
    "SELECT cart_id FROM cart WHERE user_id = ? LIMIT 1"
);
$cartStmt->bind_param("i", $user_id);
$cartStmt->execute();
$cartStmt->bind_result($cart_id);

if (!$cartStmt->fetch()) {
    echo '<main><div class="empty-box">Your cart is empty.</div></main>';
    include __DIR__ . "/../includes/footer.php";
    exit();
}
$cartStmt->close();

$sql = "
    SELECT ci.item_id, p.id AS pid, p.name, p.price, p.image_url, ci.quantity
    FROM cart_items ci
    JOIN products p ON p.id = ci.product_id
    WHERE ci.cart_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cart_id);
$stmt->execute();
$res = $stmt->get_result();
?>


<style>
.cart-container {
    max-width: var(--max-width);
    margin: 2.5rem auto;
    padding: 0 1.5rem;
}

.cart-title {
    font-size: 2.4rem;
    font-family: var(--font-instrument);
    margin-bottom: 2rem;
    color: var(--text-primary);
}

.cart-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2.5rem;
    align-items: start;
}

.cart-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.cart-card {
    background: var(--bg-secondary);
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
    padding: 1.5rem;
    height: auto;
    display: flex;
    flex-direction: column;
    transition: all 0.3s ease;
}

.cart-thumb {
    width: 100%;
    height: 160px;
    object-fit: contain;
    background: var(--bg-primary);
    padding: 12px;
    border-radius: var(--radius);
    margin-bottom: 1rem;
}

.cart-card h3 {
    font-size: 1.1rem;
    font-family: var(--font-inter);
    line-height: 1.4;
    min-height: 48px;
    overflow: hidden;
    margin-bottom: 0.8rem;
    color: var(--text-primary);
}

.price {
    font-size: 1.3rem;
    font-weight: 700;
    font-family: var(--font-instrument);
    margin-bottom: 1rem;
    color: var(--text-primary);
}

.qty-form { 
    display: flex; 
    gap: 0.8rem; 
    margin-bottom: 1rem;
    align-items: center;
}

.qty-form input {
    width: 70px;
    padding: 10px 12px;
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
    font-family: var(--font-inter);
    font-size: 0.95rem;
    transition: border-color 0.3s ease;
}

.qty-form input:focus {
    outline: none;
    border-color: var(--button-color);
}

.qty-form button {
    padding: 10px 18px;
    background: var(--button-color);
    color: white;
    border: none;
    border-radius: var(--radius);
    font-family: var(--font-instrument);
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
}


.remove-link {
    margin-top: auto;
    padding: 10px 16px;
    background: #dc3545;
    color: #fff;
    border-radius: var(--radius);
    text-decoration: none;
    text-align: center;
    font-family: var(--font-instrument);
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.right-side { 
    display: flex; 
    flex-direction: column; 
    gap: 1.8rem;
    position: sticky;
    top: 100px;
    height: fit-content;
}

.success-box {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    padding: 14px 18px;
    color: #155724;
    border-radius: var(--radius);
    animation: fadeIn 0.4s ease;
    font-family: var(--font-inter);
    font-size: 0.95rem;
}

.address-box {
    background: var(--bg-secondary);
    padding: 1.8rem;
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
}

.address-box h3 { 
    margin-bottom: 1.4rem; 
    font-size: 1.5rem;
    font-family: var(--font-instrument);
    color: var(--text-primary);
}

.address-box input {
    width: 100%;
    padding: 12px 14px;
    margin-bottom: 12px;
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
    font-family: var(--font-inter);
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: var(--bg-primary);
}

.address-box input:focus {
    outline: none;
    border-color: var(--button-color);
    box-shadow: 0 0 0 3px rgba(128, 90, 213, 0.1);
}

.address-box input::placeholder {
    color: var(--text-secondary);
    opacity: 0.7;
}

.save-btn {
    width: 100%;
    padding: 14px;
    background: var(--button-color);
    color: #fff;
    border: none;
    border-radius: var(--radius);
    font-family: var(--font-instrument);
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 8px;
}

.summary-card {
    background: var(--bg-secondary);
    padding: 1.8rem;
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
}

.summary-card h3 { 
    font-size: 1.5rem; 
    margin-bottom: 1.2rem;
    font-family: var(--font-instrument);
    color: var(--text-primary);
}

.summary-line { 
    display: flex; 
    justify-content: space-between; 
    margin-bottom: 0.8rem;
    font-family: var(--font-inter);
    font-size: 0.95rem;
    color: var(--text-secondary);
}

.summary-line span:last-child {
    font-weight: 600;
    color: var(--text-primary);
}

.summary-total {
    margin-top: 1.2rem;
    border-top: 2px solid var(--border-color);
    padding-top: 1.2rem;
    font-size: 1.4rem;
    font-weight: 700;
    font-family: var(--font-instrument);
    display: flex;
    justify-content: space-between;
    color: var(--text-primary);
}

.btn-checkout {
    display: block;
    padding: 16px;
    text-align: center;
    background: var(--button-color);
    color: #fff;
    border-radius: var(--radius);
    margin-top: 1.6rem;
    font-family: var(--font-instrument);
    font-size: 1.05rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.disabled-btn { 
    background: #6c757d !important; 
    cursor: not-allowed !important;
    opacity: 0.6;
}

.empty-box {
    max-width: 600px;
    margin: 4rem auto;
    padding: 3rem;
    text-align: center;
    background: var(--bg-secondary);
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
    font-family: var(--font-inter);
    font-size: 1.1rem;
    color: var(--text-secondary);
}

@media (max-width: 900px) {
    .cart-layout { 
        grid-template-columns: 1fr;
    }
    
    .cart-grid { 
        grid-template-columns: 1fr;
        order: 2;
    }
    
    .right-side {
        order: 1;
        position: static;
    }
    
    .cart-card {
        height: auto;
    }
}

@media (max-width: 600px) {
    .cart-title {
        font-size: 2rem;
    }
    
    .address-box,
    .summary-card {
        padding: 1.4rem;
    }
    
    .qty-form {
        flex-wrap: wrap;
    }
    
    .qty-form input {
        flex: 1;
        min-width: 80px;
    }
    
    .qty-form button {
        flex: 1;
    }
}
</style>

<main>
<section class="cart-container">

    <h2 class="cart-title">Your Cart</h2>

    <div class="cart-layout">

        <div class="cart-grid">
            <?php
            $grand = 0;
            while ($row = $res->fetch_assoc()):

                $line = $row["price"] * $row["quantity"];
                $grand += $line;
                ?>
            <div class="cart-card">

                <img class="cart-thumb"
                    src="/thakran-electronics/assets/images/products/<?= $row[
                        "image_url"
                    ] ?>"
                    alt="<?= htmlspecialchars($row["name"]) ?>">

                <h3><?= htmlspecialchars($row["name"]) ?></h3>

                <div class="price">$<?= number_format($row["price"], 2) ?></div>

                <form class="qty-form" method="POST" action="/thakran-electronics/cart/update.php">
                    <input type="hidden" name="item_id" value="<?= $row[
                        "item_id"
                    ] ?>">
                    <input type="number" name="quantity" min="1" value="<?= $row[
                        "quantity"
                    ] ?>" aria-label="Quantity">
                    <button type="submit">Update</button>
                </form>

                <a class="remove-link"
                    href="/thakran-electronics/cart/remove.php?id=<?= $row[
                        "item_id"
                    ] ?>">
                    Remove
                </a>

            </div>
            <?php
            endwhile;
            ?>
        </div>

        <div class="right-side">

            <?php if (!empty($_SESSION["address_saved"])): ?>
                <div class="success-box">✓ Address saved successfully!</div>
                <?php unset($_SESSION["address_saved"]); ?>
            <?php endif; ?>

            <div class="address-box">
                <h3>Delivery Address</h3>

                <form method="POST">

                    <input type="text" name="full_name" placeholder="Full Name"
                           required value="<?= htmlspecialchars(
                               $address["full_name"] ?? ""
                           ) ?>">

                    <input type="tel" name="phone" placeholder="Phone Number"
                           required value="<?= htmlspecialchars(
                               $address["phone"] ?? ""
                           ) ?>">

                    <input type="text" name="pincode" placeholder="Postal Code"
                           required value="<?= htmlspecialchars(
                               $address["pincode"] ?? ""
                           ) ?>">

                    <input type="text" name="address_line1" placeholder="Street Address"
                           required value="<?= htmlspecialchars(
                               $address["address_line1"] ?? ""
                           ) ?>">

                    <input type="text" name="address_line2" placeholder="Apartment / Suite"
                           value="<?= htmlspecialchars(
                               $address["address_line2"] ?? ""
                           ) ?>">

                    <input type="text" name="city" placeholder="City"
                           required value="<?= htmlspecialchars(
                               $address["city"] ?? ""
                           ) ?>">

                    <input type="text" name="state" placeholder="Province / State"
                           required value="<?= htmlspecialchars(
                               $address["state"] ?? ""
                           ) ?>">

                    <input type="text" name="country" placeholder="Country"
                           required value="<?= htmlspecialchars(
                               $address["country"] ?? "Canada"
                           ) ?>">

                    <button class="save-btn" type="submit" name="save_address">Save Address</button>
                </form>
            </div>

            <div class="summary-card">

                <h3>Order Summary</h3>

                <div class="summary-line">
                    <span>Subtotal:</span> 
                    <span>$<?= number_format($grand, 2) ?></span>
                </div>
                <div class="summary-line">
                    <span>Shipping:</span> 
                    <span>FREE</span>
                </div>

                <div class="summary-total">
                    <span>Total:</span>
                    <span>$<?= number_format($grand, 2) ?></span>
                </div>

                <?php if ($address): ?>
                    <a class="btn-checkout" href="/thakran-electronics/cart/checkout.php">Proceed to Checkout</a>
                <?php else: ?>
                    <button class="btn-checkout disabled-btn" disabled>Please Fill Address First</button>
                <?php endif; ?>

            </div>

        </div>

    </div>

</section>
</main>

<script>
setTimeout(() => {
    const box = document.querySelector(".success-box");
    if (box) {
        box.style.transition = "opacity 0.3s ease, transform 0.3s ease";
        box.style.opacity = "0";
        box.style.transform = "translateY(-8px)";
        setTimeout(() => box.style.display = "none", 300);
    }
}, 3000);
</script>

<?php include __DIR__ . "/../includes/footer.php"; ?>
