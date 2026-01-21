<?php
session_start();
require_once "conn.php";
require_once "header.php";
require_once "menu.php";

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "user") {
    header("Location: connection.php");
    exit;
}

$sql = "SELECT id, name, description, price, image, stock FROM products where stock> 0 ORDER BY id DESC";
$result = $conn->query($sql);
?>
<div class="container">
  <h1 class="mb-4">Electronics Store</h1>
  <p class="text-muted">Browse and add items to your cart.</p>

  <div class="row">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col-md-4 mb-4">
          <div class="card h-100">
            <?php if (!empty($row["image"])): ?>
              <img src="images/<?php echo htmlspecialchars($row["image"]); ?>" class="card-img-top" alt="Product image">
            <?php endif; ?>
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?php echo htmlspecialchars($row["name"]); ?></h5>
              <p class="card-text small text-muted">
                <?php echo nl2br(htmlspecialchars($row["description"])); ?>
              </p>
              <p class="fw-bold mb-1">Price: EUR <?php echo number_format($row["price"], 2); ?></p>
              <p class="text-muted mb-3">In stock: <?php echo (int)$row["stock"]; ?></p>

              <?php if ($row["stock"] > 0): ?>
                <form method="post" action="cart.php" class="mt-auto">
                  <input type="hidden" name="action" value="add">
                  <input type="hidden" name="product_id" value="<?php echo (int)$row["id"]; ?>">
                  <div class="input-group">
                    <input type="number" name="quantity" value="1" min="1"
                           max="<?php echo (int)$row["stock"]; ?>"
                           class="form-control" style="max-width: 80px;">
                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                  </div>
                </form>
              <?php else: ?>
                <span class="badge bg-secondary mt-auto">Out of stock</span>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No products available yet.</p>
    <?php endif; ?>
  </div>
</div>
<?php
require_once "footer.php";
?>
