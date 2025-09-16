<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$cart = $_SESSION['cart'] ?? [];

if (!$cart) {
    header('Location: cart.php');
    exit;
}

// Fetch user's phone number (assuming it's stored in the users table)
$user_id = $_SESSION['user_id'];
$stmt_user = $pdo->prepare("SELECT username, email, phone_number FROM users WHERE id = ?");
$stmt_user->execute([$user_id]);
$user_info = $stmt_user->fetch();

if (!$user_info || empty($user_info['phone_number'])) {
    // Redirect to a page where user can update phone number or handle this case
    // For now, we'll just proceed without SMS if phone number is missing
    $phone_number = null;
    // header('Location: profile.php?action=update_phone'); // Example redirect
    // exit;
} else {
    $phone_number = $user_info['phone_number'];
}


$placeholders = implode(',', array_fill(0, count($cart), '?'));
$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute(array_keys($cart));
$products_in_cart = $stmt->fetchAll();

$total_price = 0.0;
foreach ($products_in_cart as $product) {
    $total_price += $product['price'] * $cart[$product['id']];
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $total_price]);
    $order_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($products_in_cart as $product) {
        $stmt->execute([$order_id, $product['id'], $cart[$product['id']], $product['price']]);
    }

    $pdo->commit();

    unset($_SESSION['cart']);

    // --- SMS/OTP Integration Placeholder ---
    if ($phone_number) {
        // 1. Generate OTP
        $otp = rand(100000, 999999); // Simple 6-digit OTP

        // 2. Store OTP in database (e.g., in a new 'otps' table or users table)
        // You'd need a table like: CREATE TABLE otps (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT, otp VARCHAR(6), expires_at DATETIME, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
        // $stmt_otp = $pdo->prepare("INSERT INTO otps (user_id, otp, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 10 MINUTE))");
        // $stmt_otp->execute([$user_id, $otp]);

        // 3. Send SMS with OTP
        $message = "Your MyStore order #$order_id has been placed successfully. Your OTP for confirmation is: $otp. This OTP is valid for 10 minutes.";
        
        // --- REPLACE THIS WITH YOUR ACTUAL SMS GATEWAY API CALL ---
        // Example using a hypothetical SMS API:
        // $sms_api_url = "https://api.sms-gateway.com/send";
        // $sms_params = [
        //     'to' => $phone_number,
        //     'message' => $message,
        //     'api_key' => 'YOUR_SMS_API_KEY',
        //     'sender_id' => 'MYSTORE'
        // ];
        // $ch = curl_init($sms_api_url);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($sms_params));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $response = curl_exec($ch);
        // curl_close($ch);
        // error_log("SMS sent to $phone_number for order $order_id. Response: $response");
        // --- END SMS GATEWAY API CALL ---

        // For demonstration, just log it
        error_log("SMS Placeholder: Sent OTP $otp to $phone_number for order #$order_id.");
    }
    // --- End SMS/OTP Integration Placeholder ---

    include 'includes/header.php'; // Include header to display message
    ?>
    <div class="alert alert-success text-center" role="alert">
        <h4 class="alert-heading">Order Placed Successfully!</h4>
        <p>Your order ID is **#<?php echo $order_id; ?>**.</p>
        <?php if ($phone_number): ?>
            <p>A confirmation message with an OTP has been sent to your mobile number: **<?php echo htmlspecialchars($phone_number); ?>**.</p>
            <p>Please enter the OTP to confirm your order (OTP verification page would go here).</p>
            <!-- You would typically redirect to an OTP verification page here -->
            <!-- <a href="verify_otp.php?order_id=<?php echo $order_id; ?>" class="btn btn-info mt-3">Verify OTP</a> -->
        <?php else: ?>
            <p>A confirmation email will be sent to your registered email address.</p>
        <?php endif; ?>
        <hr>
        <a href="index.php" class="btn btn-primary">Continue Shopping</a>
    </div>
    <?php
    include 'includes/footer.php'; // Include footer
    exit; // Exit after displaying the message
} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Failed to place order for user " . $_SESSION['user_id'] . ": " . $e->getMessage());
    include 'includes/header.php'; // Include header to display error
    ?>
    <div class="alert alert-danger text-center" role="alert">
        <h4 class="alert-heading">Order Placement Failed!</h4>
        <p>There was an error processing your order. Please try again.</p>
        <hr>
        <a href="cart.php" class="btn btn-secondary">Back to Cart</a>
        <a href="index.php" class="btn btn-primary">Continue Shopping</a>
    </div>
    <?php
    include 'includes/footer.php'; // Include footer
    exit; // Exit after displaying the message
}
?>