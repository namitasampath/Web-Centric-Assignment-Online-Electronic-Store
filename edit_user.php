<?php
session_start();
require_once "conn.php";
include 'header.php';
include 'menu.php';

// Make sure user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: index.php"); // redirect if not logged in
    exit;
}

$userId = (int)$_SESSION['user']['id'];

// Fetch user data
$stmt = $conn->prepare("SELECT name, email, user_photo FROM users WHERE id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<div class="container mt-4">
<h1>Edit Profile</h1>

<form method="POST" action="update_user.php" enctype="multipart/form-data">

    <!-- Hidden ID -->
    <input type="hidden" name="id" value="<?= $userId ?>">

    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">New Password (optional)</label>
        <input type="password" class="form-control" name="password" placeholder="Leave empty to keep old password">
    </div>

    <div class="mb-3">
        <label class="form-label">Profile Photo</label>
        <input type="file" class="form-control" name="photo">
        <?php if (!empty($user['user_photo'])): ?>
            <img src="images/users/<?= htmlspecialchars($user['user_photo']) ?>" style="max-width:80px;" class="mt-2">
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary">Update Profile</button>
    <a href="index.php" class="btn btn-secondary ms-2">Cancel</a>
</form>
</div>

<?php include 'footer.php'; ?>
