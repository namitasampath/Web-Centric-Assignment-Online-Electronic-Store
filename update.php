<?php
session_start();
require_once "conn.php";



$id   = (int)($_POST["id"] ?? 0);
$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$role = $_POST["role"] ?? "user";

if ($id > 0 && $name !== "" && $email !== "") {
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $email, $role, $id);
    $stmt->execute();
    $stmt->close();
}
header("Location: listUsers.php");
exit;
