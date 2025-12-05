<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    exit("Unauthorized");
}
require_once __DIR__ . "/../config/db.php";

$item_id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($item_id > 0) {
    $stmt = $conn->prepare("DELETE FROM cart_items WHERE item_id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $stmt->close();
}
header("Location: /thakran-electronics/cart/index.php");
exit();
