<?php
session_start();
require_once "conn.php";
require_once "header.php";
require_once "menu.php";

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "user") {
    header("Location: connection.php");
    exit;
}

if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

$user = $_SESSION["user"];
$cart =& $_SESSION["cart"];
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";

    if ($action === "add") {
        $pid = (int)($_POST["product_id"] ?? 0);
        $qty = (int)($_POST["quantity"] ?? 1);
        if ($pid > 0 && $qty > 0) {
            if (!isset($cart[$pid])) {
                $cart[$pid] = 0;
            }
            $cart[$pid] += $qty;
            $message = "Product added to cart.";
        }
    } elseif ($action === "update") {
        foreach ($_POST["qty"] ?? [] as $pid => $qty) {
            $pid = (int)$pid;
            $qty = (int)$qty;
            if ($qty <= 0) {
                unset($cart[$pid]);
            } else {
                $cart[$pid] = $qty;
            }
        }
        $message = "Cart updated.";
    } elseif ($action === "place_order") {
        if (empty($cart)) {
            $message = "Your cart is empty.";
        } else {
            if (!empty($cart)) {
                $ids = implode(",", array_map("intval", array_keys($cart)));
                $sql = "SELECT id, price, stock FROM products WHERE id IN ($ids) FOR UPDATE";
                $conn->begin_transaction();
                try {
                    $result = $conn->query($sql);
                    $products = [];
                    $total = 0;

                    while ($row = $result->fetch_assoc()) {
                        $products[$row["id"]] = $row;
                    }

                    foreach ($cart as $pid => $qty) {
                        if (!isset($products[$pid])) {
                            throw new Exception("Product not found.");
                        }
                        if ($products[$pid]["stock"] < $qty) {
                            throw new Exception("Not enough stock for product ID $pid.");
                        }
                        $total += $products[$pid]["price"] * $qty;
                    }

                    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount) VALUES (?, ?)");
                    $stmt->bind_param("id", $user["id"], $total);
                    $stmt->execute();
                    $order_id = $stmt->insert_id;
                    $stmt->close();

                    $stmtItem = $conn->prepare(
                        "INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)"
                    );
                    $stmtStock = $conn->prepare(
                        "UPDATE products SET stock = stock - ? WHERE id = ?"
                    );

                    foreach ($cart as $pid => $qty) {
                        $price = $products[$pid]["price"];
                        $stmtItem->bind_param("iiid", $order_id, $pid, $qty, $price);
                        $stmtItem->execute();

                        $stmtStock->bind_param("ii", $qty, $pid);
                        $stmtStock->execute();
                    }
                    $stmtItem->close();
                    $stmtStock->close();

                    $conn->commit();
                    $cart = [];
                    $message = "Order placed successfully. Your order ID is #" . $order_id;
                } catch (Exception $e) {
                    $conn->rollback();
                    $message = "Error placing order: " . $e->getMessage();
                }
            }
        }
    }
}

$items = [];
$total = 0;
if (!empty($cart)) {
    $ids = implode(",", array_map("intval", array_keys($cart)));
    $sql = "SELECT id, name, price FROM products WHERE id IN ($ids)";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $pid = $row["id"];
        $qty = $cart[$pid];
        $row["qty"] = $qty;
        $row["line_total"] = $row["price"] * $qty;
        $items[] = $row;
        $total += $row["line_total"];
    }
}
?>
<div class="container">
  <h1 class="mb-4">My Cart</h1>

  <?php if ($message): ?>
    <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
  <?php endif; ?>

  <?php if (empty($items)): ?>
    <p>Your cart is empty. <a href="products.php">Shop now</a>.</p>
  <?php else: ?>
    <form method="post" action="cart.php">
      <input type="hidden" name="action" value="update">
      <table class="table table-bordered align-middle">
        <thead>
          <tr>
            <th>Product</th>
            <th width="120">Price (EUR)</th>
            <th width="120">Quantity</th>
            <th width="140">Line Total (EUR)</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $item): ?>
            <tr>
              <td><?php echo htmlspecialchars($item["name"]); ?></td>
              <td><?php echo number_format($item["price"], 2); ?></td>
              <td>
                <input type="number" name="qty[<?php echo (int)$item["id"]; ?>]"
                       value="<?php echo (int)$item["qty"]; ?>" min="0"
                       class="form-control">
              </td>
              <td><?php echo number_format($item["line_total"], 2); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="3" class="text-end">Total</th>
            <th><?php echo number_format($total, 2); ?></th>
          </tr>
        </tfoot>
      </table>

      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-secondary">Update Cart</button>
      </div>
    </form>

    <form method="post" action="cart.php" class="mt-3" id="orderForm">
  <input type="hidden" name="action" value="place_order">
  <button type="button" id="placeOrderBtn" class="btn btn-success">
    Place Order
  </button>
</form>

  <?php endif; ?>
</div>
<div id="confetti-container"></div>
<style>
#confetti-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  overflow: hidden;
  z-index: 9999;
}

.confetti {
  position: absolute;
  font-size: 26px;
  animation: fall 3s linear forwards;
}

@keyframes fall {
  0% {
    transform: translateY(-10vh) rotate(0deg);
    opacity: 1;
  }
  100% {
    transform: translateY(110vh) rotate(360deg);
    opacity: 0;
  }
}
</style>

<script>
document.getElementById("placeOrderBtn").addEventListener("click", function () {
  const emojis = ["🎉", "🎊", "🛍️", "🎁", "✨"];
  const container = document.getElementById("confetti-container");

  for (let i = 0; i < 75; i++) {
    const confetti = document.createElement("span");
    confetti.className = "confetti";
    confetti.textContent = emojis[Math.floor(Math.random() * emojis.length)];
    confetti.style.left = Math.random() * 100 + "vw";
    confetti.style.animationDuration = (2 + Math.random() * 2) + "s";

    container.appendChild(confetti);
    setTimeout(() => confetti.remove(), 4000);
  }

  setTimeout(() => {
    document.getElementById("orderForm").submit();
  }, 1000);
});
</script>

<?php
require_once "footer.php";
?>

