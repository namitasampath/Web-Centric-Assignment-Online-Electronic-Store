<?php
session_start();
include "conn.php";
include "header.php";
include "menu.php";

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "user") {
    header("Location: connection.php");
    exit;
}

$user = $_SESSION["user"];

$sql = "SELECT id, total_amount, status, created_at
        FROM orders
        WHERE user_id = ?
        ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user["id"]);
$stmt->execute();
$ordersResult = $stmt->get_result();
$stmt->close();
?>
<div class="container">
  <h1 class="mb-4">My Orders</h1>

  <?php if ($ordersResult->num_rows === 0): ?>
    <p>You have not placed any orders yet.</p>
  <?php else: ?>
    <?php while ($order = $ordersResult->fetch_assoc()): ?>
      <?php
        $order_id = (int)$order["id"];
        $stmtItems = $conn->prepare(
          "SELECT oi.quantity, oi.unit_price, p.name
           FROM order_items oi
           JOIN products p ON oi.product_id = p.id
           WHERE oi.order_id = ?"
        );
        $stmtItems->bind_param("i", $order_id);
        $stmtItems->execute();
        $itemsResult = $stmtItems->get_result();
        $stmtItems->close();
      ?>
      <div class="card mb-3">
        <div class="card-header">
          <strong>Order #<?php echo $order_id; ?></strong>
          <span class="ms-3">Placed on <?php echo htmlspecialchars($order["created_at"]); ?></span>
          <span class="badge bg-info text-dark ms-3">
            Status: <?php echo htmlspecialchars($order["status"]); ?>
          </span>
        </div>
        <div class="card-body">
          <ul class="list-group mb-3">
            <?php while ($item = $itemsResult->fetch_assoc()): ?>
              <li class="list-group-item d-flex justify-content-between">
                <span>
                  <?php echo htmlspecialchars($item["name"]); ?>
                  × <?php echo (int)$item["quantity"]; ?>
                </span>
                <span>
                  EUR <?php echo number_format($item["unit_price"] * $item["quantity"], 2); ?>
                </span>
              </li>
            <?php endwhile; ?>
          </ul>
          <p class="fw-bold">
            Total: EUR <?php echo number_format($order["total_amount"], 2); ?>
          </p>
        </div>
      </div>
    <?php endwhile; ?>
  <?php endif; ?>
</div>
<?php
include "footer.php";
?>
