<?php
session_start();
require_once "header.php";
require_once "menu.php";

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    header("Location: connection.php");
    exit;
}
?>
<div class="container">
  <h1 class="mb-4">Admin Dashboard</h1>
  <p class="text-muted">Manage products, orders, and customers.</p>

  <div class="row">
    <div class="col-md-4 mb-3">
      <a href="admin_products.php" class="btn btn-outline-primary w-100">Manage Products</a>
    </div>
    <div class="col-md-4 mb-3">
      <a href="admin_orders.php" class="btn btn-outline-primary w-100">Manage Orders</a>
    </div>
    <div class="col-md-4 mb-3">
      <a href="listUsers.php" class="btn btn-outline-primary w-100">View Customers</a>
    </div>
  </div>
</div>
<?php
require_once "footer.php";
?>
