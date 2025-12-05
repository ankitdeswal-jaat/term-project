<?php
ob_start();
session_start();

if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] != 1) {
    header("Location: /thakran-electronics/login.php");
    exit();
}

require_once __DIR__ . "/../config/db.php";

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

if ($id > 0) {
    $stmt = $conn->prepare(
        "SELECT image_url FROM products WHERE id = ? LIMIT 1"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($img);
    $stmt->fetch();
    $stmt->close();

    if (!empty($img)) {
        $imagePath = __DIR__ . "/../assets/images/products/" . $img;

        if (file_exists($imagePath)) {
            @unlink($imagePath);
        }
    }

    $del = $conn->prepare("DELETE FROM products WHERE id = ?");
    $del->bind_param("i", $id);
    $del->execute();
    $del->close();
}

header("Location: /thakran-electronics/admin/products.php?deleted=1");
exit();
ob_end_flush();
