<?php
session_start();
require_once "conn.php";
require_once "header.php";
require_once "menu.php";

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $error_message = "Please enter both email and password.";
    } else {
        $sql = "SELECT id, name, email, password_hash, role FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();

            if ($user && password_verify($password, $user["password_hash"])) {
                $_SESSION["user"] = [
                    "id"    => $user["id"],
                    "name"  => $user["name"],
                    "email" => $user["email"],
                    "role"  => $user["role"],
                ];
 
                if ($user["role"] === "admin") {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: products.php");
                }
                exit;
            } else {
                $error_message = "Invalid email or password.";
            }
        } else {
            $error_message = "Database error.";
        }
    }
}
?>
<div class="container">
  <h1 class="mb-4">Login</h1>
  <?php if ($error_message): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
  <?php endif; ?>
  <form method="post" class="col-md-6">
    <div class="mb-3">
      <label class="form-label">Email address</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Login</button>
  </form>
</div>
<?php
require_once "footer.php";
?>
