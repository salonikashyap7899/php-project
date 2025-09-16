<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $phone_number = trim($_POST['phone_number']); // New field

    if ($username && $email && $password && $phone_number) { // Validate new field
        // Basic phone number validation (you might want more robust validation)
        if (!preg_match('/^[0-9]{10,15}$/', $phone_number)) {
            $error = "Please enter a valid phone number (10-15 digits).";
        } else {
            $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? OR username = ? OR phone_number = ?'); // Check phone number uniqueness
            $stmt->execute([$email, $username, $phone_number]);
            if ($stmt->fetch()) {
                $error = "User with this email, username, or phone number already exists.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('INSERT INTO users (username, email, password, phone_number) VALUES (?, ?, ?, ?)'); // Insert new field
                $stmt->execute([$username, $email, $hash, $phone_number]);
                header('Location: login.php');
                exit;
            }
        }
    } else {
        $error = "Please fill all fields.";
    }
}

include 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-sm p-4">
            <h3 class="mb-4 text-center">Register</h3>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="post" novalidate>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required autofocus />
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required />
                </div>
                <div class="mb-3">
                    <label for="phone_number" class="form-label">Mobile Number</label>
                    <input type="tel" class="form-control" id="phone_number" name="phone_number" required pattern="[0-9]{10,15}" title="Phone number must be 10-15 digits" />
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required />
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
            <p class="mt-3 text-center">Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>