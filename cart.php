<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$cart = $_SESSION['cart'] ?? [];

$products_in_cart = [];
$total_price = 0.0;

if ($cart) {
    $placeholders = implode(',', array_fill(0, count($cart), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute(array_keys($cart));
    $products_in_cart = $stmt->fetchAll();

    foreach ($products_in_cart as $product) {
        $total_price += $product['price'] * $cart[$product['id']];
    }
}

include 'includes/header.php';
?>

<h2 class="mb-4">Your Cart</h2>

<?php if (!$cart): ?>
    <div class="alert alert-info">Your cart is empty.</div>
    <a href="index.php" class="btn btn-primary">Continue Shopping</a>
<?php else: ?>
    <div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>Product</th>
                <th class="text-center">Price</th>
                <th class="text-center">Quantity</th>
                <th class="text-center">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products_in_cart as $product): ?>
            <tr>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td class="text-center">₹<?php echo number_format($product['price'], 2); ?></td>
                <td class="text-center"><?php echo $cart[$product['id']]; ?></td>
                <td class="text-center">₹<?php echo number_format($product['price'] * $cart[$product['id']], 2); ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="fw-bold">
                <td colspan="3" class="text-end">Total:</td>
                <td class="text-center">₹<?php echo number_format($total_price, 2); ?></td>
            </tr>
        </tbody>
    </table>
    </div>
    <form method="post" action="checkout.php">
        <button type="submit" class="btn btn-success">Checkout</button>
        <a href="index.php" class="btn btn-secondary ms-2">Continue Shopping</a>
    </form>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>