<?php
include "conn.php";

// Fetch 3 latest product images
$stmt = $conn->query("SELECT image, name FROM products ORDER BY id DESC LIMIT 3");
$products = $stmt->fetch_all(MYSQLI_ASSOC);
?>

<?php if (count($products) > 0): ?>
<div id="productCarousel" class="carousel slide mx-auto" data-bs-ride="carousel"
     style="max-width:600px;">

  <div class="carousel-inner">

    <?php foreach ($products as $index => $product): ?>
      <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">

        <img src="images/<?php echo htmlspecialchars($product['image']); ?>"
             class="d-block mx-auto"
             alt="<?php echo htmlspecialchars($product['name']); ?>"
             style="max-height:350px; width:auto;">

        <div class="carousel-caption d-none d-md-block">
          <h6><?php echo htmlspecialchars($product['name']); ?></h6>
        </div>

      </div>
    <?php endforeach; ?>

  </div>

  <!-- Controls -->
  <button class="carousel-control-prev" type="button"
          data-bs-target="#productCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>

  <button class="carousel-control-next" type="button"
          data-bs-target="#productCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>

</div>
<?php endif; ?>
