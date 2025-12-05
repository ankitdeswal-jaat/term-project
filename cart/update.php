<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    exit("Unauthorized");
}
require_once __DIR__ . "/../config/db.php";

$item_id = isset($_POST["item_id"]) ? (int) $_POST["item_id"] : 0;
$qty = isset($_POST["quantity"]) ? max(1, (int) $_POST["quantity"]) : 1;

if ($item_id > 0) {
    $stmt = $conn->prepare(
        "UPDATE cart_items SET quantity = ? WHERE item_id = ?"
    );
    $stmt->bind_param("ii", $qty, $item_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: /thakran-electronics/cart/index.php");
exit();
