<?php
require 'db.php';
include 'includes/header.php';

// Fetch products for display
$stmt = $pdo->query('SELECT * FROM products ORDER BY created_at DESC LIMIT 12');
$products = $stmt->fetchAll();
?>

<!-- Hero Carousel -->
<div id="heroCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
  <div class="carousel-inner rounded-3 shadow-sm">
    <div class="carousel-item active">
      <img src="./assets/imges/hero1.jpg" class="d-block w-100" alt="Fashion Sale Banner" style="height: 400px; object-fit: cover;">
      <div class="carousel-caption d-none d-md-block text-start" style="bottom: 30px; left: 50px;">
        <h1 class="display-4 fw-bold text-white">Biggest Fashion Sale</h1>
        <p class="lead text-white">Up to 70% off on top brands</p>
        <a href="index.php" class="btn btn-danger btn-lg px-4">Shop Now</a>
      </div>
    </div>
    <div class="carousel-item">
      <img src="./assets/imges/hero2.jpg.jpg" class="d-block w-100" alt="New Arrivals" style="height: 400px; object-fit: cover;">
      <div class="carousel-caption d-none d-md-block text-start" style="bottom: 30px; left: 50px;">
        <h1 class="display-4 fw-bold text-white">New Arrivals</h1>
        <p class="lead text-white">Latest trends just for you</p>
        <a href="index.php" class="btn btn-danger btn-lg px-4">Explore</a>
      </div>
    </div>
    <div class="carousel-item">
      <img src="./assets/imges/hero3.jpg" class="d-block w-100" alt="Exclusive Offers" style="height: 400px; object-fit: cover;">
      <div class="carousel-caption d-none d-md-block text-start" style="bottom: 30px; left: 50px;">
        <h1 class="display-4 fw-bold text-white">Exclusive Offers</h1>
        <p class="lead text-white">Members get extra 10% off</p>
        <a href="index.php" class="btn btn-danger btn-lg px-4">Join Now</a>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<!-- Featured Categories (Optional) -->
<div class="mb-5">
  <h3 class="mb-4 fw-bold">Shop by Category</h3>
  <div class="row g-3">
    <div class="col-6 col-md-3">
      <a href="#" class="text-decoration-none text-dark">
        <div class="category-card p-3 text-center bg-white rounded shadow-sm">
          <img src="./assets/imges/tshirt.jpg" alt="T-Shirts" class="img-fluid mb-2" style="height: 100px; object-fit: contain;">
          <h6>T-Shirts</h6>
        </div>
      </a>
    </div>
    <div class="col-6 col-md-3">
      <a href="#" class="text-decoration-none text-dark">
        <div class="category-card p-3 text-center bg-white rounded shadow-sm">
          <img src="./assets/imges/jeans.jpg" alt="Jeans" class="img-fluid mb-2" style="height: 100px; object-fit: contain;">
          <h6>Jeans</h6>
        </div>
      </a>
    </div>
    <div class="col-6 col-md-3">
      <a href="#" class="text-decoration-none text-dark">
        <div class="category-card p-3 text-center bg-white rounded shadow-sm">
          <img src="./assets/imges/sneakers.jpeg" alt="Sneakers" class="img-fluid mb-2" style="height: 100px; object-fit: contain;">
          <h6>Sneakers</h6>
        </div>
      </a>
    </div>
    <div class="col-6 col-md-3">
      <a href="#" class="text-decoration-none text-dark">
        <div class="category-card p-3 text-center bg-white rounded shadow-sm">
          <img src="./assets/imges/watches.jpeg" alt="Watches" class="img-fluid mb-2" style="height: 100px; object-fit: contain;">
          <h6>Watches</h6>
        </div>
      </a>
    </div>
  </div>
</div>

<!-- Product Grid -->
<h3 class="mb-4 fw-bold">Trending Products</h3>
<div class="row g-4">
  <?php foreach ($products as $product): ?>
    <div class="col-6 col-md-4 col-lg-3">
      <div class="card h-100 shadow-sm border-0 rounded-3 product-card">
        <?php if ($product['image']): ?>
          <img src="./assets/imges/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>" style="height: 280px; object-fit: cover; border-top-left-radius: 12px; border-top-right-radius: 12px;">
        <?php else: ?>
          <img src="https://via.placeholder.com/300x280?text=No+Image" class="card-img-top" alt="No Image" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
        <?php endif; ?>
        <div class="card-body d-flex flex-column">
          <h6 class="card-title text-truncate" title="<?php echo htmlspecialchars($product['name']); ?>"><?php echo htmlspecialchars($product['name']); ?></h6>
          <p class="card-text text-muted small flex-grow-1"><?php echo htmlspecialchars($product['description']); ?></p>
          <div class="d-flex justify-content-between align-items-center mt-3">
            <span class="fw-bold text-danger fs-5">₹<?php echo number_format($product['price'], 2); ?></span>
            <form method="post" action="add_to_cart.php" class="d-flex align-items-center">
              <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>" />
              <input type="number" name="quantity" value="1" min="1" class="form-control form-control-sm me-2" style="width: 60px;" required />
              <button type="submit" class="btn btn-danger btn-sm px-3">Add</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<style>
  /* Additional Myntra-like styling */
  .product-card:hover {
    box-shadow: 0 8px 20px rgb(0 0 0 / 0.15);
    transform: translateY(-5px);
    transition: all 0.3s ease;
  }
  .category-card:hover {
    box-shadow: 0 6px 15px rgb(0 0 0 / 0.1);
    transform: translateY(-3px);
    transition: all 0.3s ease;
  }
</style>

<?php include 'includes/footer.php'; ?>
