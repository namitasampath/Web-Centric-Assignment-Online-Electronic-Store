<?php
require_once "header.php";
require_once "menu.php";
require_once "conn.php";

$message = "";
/* Post does not display data in url. It is more secure in comparison to get. */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Default photo (IMPORTANT: NOT NULL)
    $photo = "default.png";

    // Handle photo upload (FIELD NAME MUST MATCH)
    if (!empty($_FILES["user_photo"]["name"])) {
        if (!is_dir("images/users")) {
            mkdir("images/users", 0777, true);
        }

        $allowedTypes = ["image/jpeg", "image/png", "image/jpg", "image/webp"];

        if (in_array($_FILES["user_photo"]["type"], $allowedTypes)) {
            $filename = time() . "_" . basename($_FILES["user_photo"]["name"]);
            $target = "images/users/" . $filename;

            if (move_uploaded_file($_FILES["user_photo"]["tmp_name"], $target)) {
                $photo = $filename;
            }
        }
    }

    $name  = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $pass1 = $_POST["password"] ?? "";
    $pass2 = $_POST["confirm_password"] ?? "";

    if ($name === "" || $email === "" || $pass1 === "" || $pass2 === "") {
        $message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Enter a valid email.";
    } elseif ($pass1 !== $pass2) {
        $message = "Passwords do not match.";
    } else {
        $hash = password_hash($pass1, PASSWORD_DEFAULT);
        $role = "user";

        $stmt = $conn->prepare(
            "INSERT INTO users (name, email, password_hash, role, user_photo)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("sssss", $name, $email, $hash, $role, $photo);

        if ($stmt->execute()) {
            $message = "Registration successful. You can now log in.";
        } else {
            $message = "Error: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<div class="container">
  <h1 class="mb-4">Customer Registration</h1>

  <?php if ($message): ?>
    <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" class="col-md-6">

    <div class="mb-3">
      <label class="form-label">Full Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Confirm Password</label>
      <input type="password" name="confirm_password" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Profile Photo</label>
      <input type="file" name="user_photo" class="form-control" accept="image/*">
    </div>

    <button type="submit" class="btn btn-primary">Register</button>
  </form>
</div>

<?php require_once "footer.php"; ?>