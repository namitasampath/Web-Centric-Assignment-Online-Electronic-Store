<?php
session_start();
require_once "conn.php";
require_once "header.php";
require_once "menu.php";                                              

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    header("Location: connection.php");
    exit;
}

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$editing = $id > 0;
$name = $description = $image = "";
$price = 0;
$stock = 0;

if ($editing) {
    $stmt = $conn->prepare("SELECT name, description, price, stock, image FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($name, $description, $price, $stock, $image);
    if (!$stmt->fetch()) {
        $editing = false;
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $price = (float)($_POST["price"] ?? 0);
    $stock = (int)($_POST["stock"] ?? 0);
    $uploadImage = $image;

    if (!empty($_FILES["image"]["name"])) {
        if (!is_dir("images")) {
            mkdir("images", 0777, true);
        }
        $filename = time() . "_" . basename($_FILES["image"]["name"]);
        $target = "images/" . $filename;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target)) {
            $uploadImage = $filename;
        }
    }

    if ($editing) {
        $stmt = $conn->prepare(
          "UPDATE products SET name=?, description=?, price=?, stock=?, image=? WHERE id=?"
        );
        $stmt->bind_param("ssdisi", $name, $description, $price, $stock, $uploadImage, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: admin_products.php");
        exit;
    } else {
        $stmt = $conn->prepare(
          "INSERT INTO products (name, description, price, stock, image)
           VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("ssdis", $name, $description, $price, $stock, $uploadImage);
        $stmt->execute();
        $stmt->close();
        header("Location: admin_products.php");
        exit;
    }
}
?>
<div class="container">
  <h1 class="mb-4">
    <?php echo $editing ? "Edit Product" : "Add New Product"; ?>
  </h1>

  <form method="post" enctype="multipart/form-data" class="col-md-8">
    <div class="mb-3">
      <label class="form-label">Product Name</label>
      <input type="text" name="name" class="form-control"
             value="<?php echo htmlspecialchars($name); ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea name="description" class="form-control" rows="3"><?php
        echo htmlspecialchars($description);
      ?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">Price (EUR)</label>
      <input type="number" step="0.01" name="price" class="form-control"
             value="<?php echo htmlspecialchars($price); ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Stock</label>
      <input type="number" name="stock" class="form-control"
             value="<?php echo htmlspecialchars($stock); ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Image</label>
      <input type="file" name="image" class="form-control">
      <?php if ($image): ?>
        <p class="mt-2">Current: <img src="images/<?php echo htmlspecialchars($image); ?>"
                                      style="max-width:80px;"></p>
      <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-success">
      <?php echo $editing ? "Update Product" : "Create Product"; ?>
    </button>
    <a href="admin_products.php" class="btn btn-secondary ms-2">Cancel</a>
  </form>
</div>
<?php
require_once "footer.php";
?>
