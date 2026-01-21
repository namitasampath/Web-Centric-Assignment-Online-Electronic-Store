<?php
session_start();
require_once "conn.php";

// Make sure user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$userId = (int)$_POST['id'];
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);
$photo = null;

// Handle photo upload
if (!empty($_FILES['photo']['name'])) {
    if (!is_dir("images/users")) mkdir("images/users", 0777, true);
    $filename = time() . "_" . basename($_FILES['photo']['name']);
    move_uploaded_file($_FILES['photo']['tmp_name'], "images/users/" . $filename);
    $photo = $filename;
}

// Build query dynamically
$sql = "UPDATE users SET name=?, email=?";
$params = [$name, $email];
$types = "ss";

if (!empty($password)) {
    $sql .= ", password_hash=?";
    $params[] = password_hash($password, PASSWORD_DEFAULT);
    $types .= "s";
}

if ($photo) {
    $sql .= ", user_photo=?";
    $params[] = $photo;
    $types .= "s";
}

$sql .= " WHERE id=?";
$params[] = $userId;
$types .= "i";

// Execute
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$stmt->close();

// Update session info
$_SESSION['user']['name'] = $name;
if ($photo) $_SESSION['user']['user_photo'] = $photo;

// Redirect to home page
header("Location: index.php");
exit;
?>
