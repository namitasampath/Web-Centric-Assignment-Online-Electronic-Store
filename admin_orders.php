<?php
session_start();
require_once "conn.php";
require_once "header.php";
require_once "menu.php";

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    header("Location: connection.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $order_id = (int)($_POST["order_id"] ?? 0);
    $status   = $_POST["status"] ?? "pending";
    if ($order_id > 0) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);
        $stmt->execute();
        $stmt->close();
    }
}

$sql = "SELECT o.id, o.total_amount, o.status, o.created_at,
               u.name AS customer_name, u.email
        FROM orders o
        JOIN users u ON o.user_id = u.id
        ORDER BY o.created_at DESC";
$result = $conn->query($sql);
?>
<div class="container">
  <h1 class="mb-4">Manage Orders</h1>

  <?php if ($result && $result->num_rows > 0): ?>
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>ID</th>
          <th>Customer</th>
          <th>Email</th>
          <th>Total (EUR)</th>
          <th>Status</</th>
          <th>Created</th>
          <th width="160">Update Status</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo (int)$row["id"]; ?></td>
            <td><?php echo htmlspecialchars($row["customer_name"]); ?></td>
            <td><?php echo htmlspecialchars($row["email"]); ?></td>
            <td><?php echo number_format($row["total_amount"], 2); ?></td>
            <td><?php echo htmlspecialchars($row["status"]); ?></td>
            <td><?php echo htmlspecialchars($row["created_at"]); ?></td>
            <td>
              <form method="post" action="admin_orders.php" class="d-flex">
                <input type="hidden" name="order_id" value="<?php echo (int)$row["id"]; ?>">
                <select name="status" class="form-select form-select-sm me-1">
                  <?php
                    $statuses = ['pending','processing','shipped','delivered','cancelled'];
                    foreach ($statuses as $s):
                  ?>
                    <option value="<?php echo $s; ?>"
                      <?php if ($row["status"] === $s) echo "selected"; ?>>
                      <?php echo ucfirst($s); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-sm btn-primary">Save</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No orders found.</p>
  <?php endif; ?>
</div>
<?php
require_once "footer.php";
?>
