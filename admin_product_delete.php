<?php
session_start();
require_once "conn.php";

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    header("Location: connection.php");
    exit;
}

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: admin_products.php");
exit;
