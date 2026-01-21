<?php
session_start();
require_once "conn.php";
require_once "header.php";
require_once "menu.php";

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$stmt = $conn->prepare("SELECT name, email, role FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($name, $email, $role);
if (!$stmt->fetch()) {
    $stmt->close();
    header("Location: listUsers.php");
    exit;
}
$stmt->close();
?>
<div class="container">
  <h1 class="mb-4">Edit User</h1>
  <form method="post" action="update.php" class="col-md-6">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Role</label>
      <select name="role" class="form-select">
        <option value="user" <?php if ($role === "user") echo "selected"; ?>>User</option>
        <option value="admin" <?php if ($role === "admin") echo "selected"; ?>>Admin</option>
      </select>
    </div>
    <button type="submit" class="btn btn-success">Save</button>
    <a href="listUsers.php" class="btn btn-secondary ms-2">Cancel</a>
  </form>
</div>
<?php
require_once "footer.php";
?>
