<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: /thakran-electronics/login.php");
    exit();
}
require_once __DIR__ . "/../config/db.php";

$user_id = (int) $_SESSION["user_id"];

$cartStmt = $conn->prepare(
    "SELECT cart_id FROM cart WHERE user_id = ? LIMIT 1"
);
$cartStmt->bind_param("i", $user_id);
$cartStmt->execute();
$cartStmt->bind_result($cart_id);
if (!$cartStmt->fetch()) {
    header("Location: /thakran-electronics/cart/index.php");
    exit();
}
$cartStmt->close();

$sql =
    "SELECT ci.product_id, ci.quantity, p.price FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.cart_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cart_id);
$stmt->execute();
$res = $stmt->get_result();

$total = 0;
$items = [];
while ($r = $res->fetch_assoc()) {
    $items[] = $r;
    $total += $r["price"] * $r["quantity"];
}
$stmt->close();

if (count($items) === 0) {
    header("Location: /thakran-electronics/cart/index.php");
    exit();
}

$o = $conn->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
$o->bind_param("id", $user_id, $total);
$o->execute();
$order_id = $conn->insert_id;
$o->close();

$oi = $conn->prepare(
    "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)"
);
foreach ($items as $it) {
    $pid = (int) $it["product_id"];
    $q = (int) $it["quantity"];
    $p = (float) $it["price"];
    $oi->bind_param("iiid", $order_id, $pid, $q, $p);
    $oi->execute();
}
$oi->close();

$del = $conn->prepare("DELETE FROM cart_items WHERE cart_id = ?");
$del->bind_param("i", $cart_id);
$del->execute();
$del->close();
$del2 = $conn->prepare("DELETE FROM cart WHERE cart_id = ?");
$del2->bind_param("i", $cart_id);
$del2->execute();
$del2->close();

header("Location: /thakran-electronics/cart/success.php");
exit();
