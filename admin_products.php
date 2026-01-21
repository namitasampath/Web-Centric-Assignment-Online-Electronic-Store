<?php
session_start();
require_once "conn.php";
require_once "header.php";
require_once "menu.php";

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    header("Location: connection.php");
    exit;
}

$result = $conn->query("SELECT id, name, price, stock, image FROM products ORDER BY id DESC");
?>
<div class="container">
  <h1 class="mb-4">Manage Products</h1>

  <a href="admin_product_form.php" class="btn btn-primary mb-3">Add New Product</a>

  <?php if ($result && $result->num_rows > 0): ?>
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>ID</th>
          <th>Product</th>
          <th>Price (EUR)</th>
          <th>Stock</th>
          <th>Image</th>
          <th width="180">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo (int)$row["id"]; ?></td>
            <td><?php echo htmlspecialchars($row["name"]); ?></td>
            <td><?php echo number_format($row["price"], 2); ?></td>
            <td><?php echo (int)$row["stock"]; ?></td>
            <td>
              <?php if (!empty($row["image"])): ?>
                <img src="images/<?php echo htmlspecialchars($row["image"]); ?>" alt="" style="max-width:60px;">
              <?php endif; ?>
            </td>
            <td>
              <a href="admin_product_form.php?id=<?php echo (int)$row["id"]; ?>"
                 class="btn btn-sm btn-outline-secondary">Edit</a>
              <a href="admin_product_delete.php?id=<?php echo (int)$row["id"]; ?>"
                 class="btn btn-sm btn-outline-danger"
                 onclick="return confirm('Delete this product?');">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No products found.</p>
  <?php endif; ?>
</div>
<?php
require_once "footer.php";
?>
