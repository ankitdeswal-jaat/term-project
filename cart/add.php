<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: /thakran-electronics/login.php");
    exit();
}

require_once __DIR__ . "/../config/db.php";

$user_id = (int) $_SESSION["user_id"];
$product_id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

if ($product_id <= 0) {
    header("Location: /thakran-electronics/index.php");
    exit();
}

$cartStmt = $conn->prepare(
    "SELECT cart_id FROM cart WHERE user_id = ? LIMIT 1"
);
$cartStmt->bind_param("i", $user_id);
$cartStmt->execute();
$cartStmt->bind_result($cart_id);

if (!$cartStmt->fetch()) {
    $cartStmt->close();

    $create = $conn->prepare("INSERT INTO cart (user_id) VALUES (?)");
    $create->bind_param("i", $user_id);
    $create->execute();
    $cart_id = $conn->insert_id;
    $create->close();
} else {
    $cartStmt->close();
}

$check = $conn->prepare(
    "SELECT item_id FROM cart_items WHERE cart_id = ? AND product_id = ? LIMIT 1"
);
$check->bind_param("ii", $cart_id, $product_id);
$check->execute();
$check->bind_result($item_id);

if ($check->fetch()) {
    $check->close();

    $update = $conn->prepare(
        "UPDATE cart_items SET quantity = quantity + 1 WHERE item_id = ?"
    );
    $update->bind_param("i", $item_id);
    $update->execute();
    $update->close();
} else {
    $check->close();

    $insert = $conn->prepare(
        "INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, 1)"
    );
    $insert->bind_param("ii", $cart_id, $product_id);
    $insert->execute();
    $insert->close();
}

$back = $_SERVER["HTTP_REFERER"] ?? "/thakran-electronics/products.php";

header("Location: $back");
exit();
