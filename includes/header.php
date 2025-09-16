<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MyStore - Modern E-commerce</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body style="font-family: 'Poppins', sans-serif;">

<!-- ✅ Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold fs-3" href="index.php">MyStore</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
      <!-- Left Menu -->
      <ul class="navbar-nav align-items-center">
        <li class="nav-item me-3">
          <a class="nav-link" href="index.php">Home</a>
        </li>
      </ul>

      <!-- ✅ Search Form -->
      <form id="searchForm" class="d-flex position-relative" role="search" style="width: 400px; margin-left: 20px;">
        <input id="searchInput" class="form-control me-2" type="search" 
               placeholder="Search for products, brands and more..." aria-label="Search">
        <div id="searchResults" 
             class="position-absolute bg-white shadow rounded mt-1 w-100 list-group"
             style="z-index: 1000; max-height: 300px; overflow-y: auto; display:none;">
        </div>
      </form>

      <!-- Right Menu -->
      <ul class="navbar-nav align-items-center">
        <?php if (isset($_SESSION['user_id'])): ?>
          <!-- Cart -->
          <li class="nav-item me-3">
            <a class="nav-link position-relative" href="cart.php">
              <i class="fa fa-shopping-cart fa-lg"></i>
              Cart
              <?php 
                $count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
                if ($count > 0) {
                    echo "<span class='badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle'>$count</span>";
                }
              ?>
            </a>
          </li>
          <!-- User Dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-capitalize" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fa fa-user"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item me-3">
            <a class="btn btn-outline-primary" href="login.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-primary" href="register.php">Register</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- ✅ Content Wrapper -->
<div class="container my-4">
