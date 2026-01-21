<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user = $_SESSION['user'] ?? null;
$role = $user['role'] ?? null;
?>
<nav class="navbar navbar-expand-md bg-dark navbar-dark mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">M & N Electronics</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
            aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav me-auto mb-2 mb-md-0">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Home</a>
        </li>

        <?php if (!$user): ?>
          <li class="nav-item">
            <a class="nav-link" href="registration.php">Register</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="connection.php">Login</a>
          </li>
        <?php else: ?>
          <?php if ($role === 'user'): ?>
            <li class="nav-item">
              <a class="nav-link" href="products.php">Shop Electronics</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="cart.php">My Cart</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="my_orders.php">My Orders</a>
            </li>
          <?php elseif ($role === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link" href="admin_dashboard.php">Admin Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="admin_products.php">Manage Products</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="admin_orders.php">Manage Orders</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="listUsers.php">Customers</a>
            </li>
          <?php endif; ?>
        <?php endif; ?>
      </ul>

      <ul class="navbar-nav mb-2 mb-md-0">
        <?php if ($user): ?>
          <li class="nav-item">
            <span class="navbar-text text-light me-3">
              <a class="nav-link" href="edit_user.php?id=<?= $user['id'] ?>">Logged in as <?php echo htmlspecialchars($user['name']); ?></a>
            </span>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Logout</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
