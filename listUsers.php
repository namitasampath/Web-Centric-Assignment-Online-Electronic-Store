<?php
session_start();
require_once "conn.php";
require_once "header.php";
require_once "menu.php";

// Admin check
if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch users including photo
$result = $conn->query(
    "SELECT id, name, email, role, user_photo FROM users ORDER BY id DESC"
);
?>

<div class="container">
  <h1 class="mb-4">Customers</h1>

  <table class="table table-striped align-middle">
    <thead>
      <tr>
        <th>ID</th>
        <th>Photo</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th width="150">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?php echo (int)$row["id"]; ?></td>

          <td>
            <img src="images/users/<?php echo htmlspecialchars($row["user_photo"] ?: 'default.png'); ?>"
                 style="width:50px;height:50px;object-fit:cover;border-radius:50%;">
          </td>

          <td><?php echo htmlspecialchars($row["name"]); ?></td>
          <td><?php echo htmlspecialchars($row["email"]); ?></td>
          <td><?php echo htmlspecialchars($row["role"]); ?></td>
          <td>
            <a href="edit.php?id=<?php echo (int)$row["id"]; ?>"
               class="btn btn-sm btn-outline-secondary">Edit</a>

            <a href="delete.php?id=<?php echo (int)$row["id"]; ?>"
               class="btn btn-sm btn-outline-danger"
               onclick="return confirm('Delete this user?');">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php require_once "footer.php"; ?>
