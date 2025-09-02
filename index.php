<?php
session_start();
include 'C:\xampp\htdocs\TravelHub\db.php';
?>
<?php include 'includes\header.php'; ?>

<!-- Hero Section -->
<section class="hero-section d-flex align-items-center text-center text-white" 
    style="background-image: url('https://images.unsplash.com/photo-1501785888041-af3ef285b470?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80'); background-size: cover; background-position: center; height: 90vh; position: relative;">
    <div class="overlay" style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.55);"></div>
    <div class="container position-relative z-2">
        <h1 class="display-3 fw-bold mb-3">Discover Your Next Adventure</h1>
        <p class="lead mb-4">Unforgettable experiences & exclusive travel packages at your fingertips</p>
        <form class="d-flex justify-content-center">
            <input type="text" class="form-control w-50 me-2" placeholder="Search destinations, packages...">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
</section>

<!-- Featured Packages -->
<div class="container py-5">
    <h2 class="text-center mb-5 fw-bold" style="color: #1E90FF;">🌍 Featured Packages</h2>
    <div class="row g-4">
        <?php
        try {
            $stmt = $pdo->query("SELECT * FROM packages LIMIT 6");
            $packages = $stmt->fetchAll();

            if ($packages && count($packages) > 0) {
                foreach ($packages as $package) {
                    $image_url = !empty($package['photo_url']) && filter_var($package['photo_url'], FILTER_VALIDATE_URL)
                        ? htmlspecialchars($package['photo_url'])
                        : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80';
                    
                    echo '<div class="col-md-4">
                        <div class="card h-100 shadow border-0 rounded-3 overflow-hidden">
                            <img src="' . $image_url . '" class="card-img-top" alt="' . htmlspecialchars($package['name']) . '" style="height: 220px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold">' . htmlspecialchars($package['name']) . '</h5>
                                <p class="card-text flex-grow-1 text-muted">' . htmlspecialchars($package['description']) . '</p>
                                <p class="fw-bold fs-5 text-success">$' . number_format($package['price'], 2) . '</p>';
                    if (isset($_SESSION['user_id'])) {
                        echo '<a href="booking.php?package_id=' . $package['id'] . '" class="btn btn-primary mt-auto">Book Now</a>';
                    } else {
                        echo '<a href="login.php" class="btn btn-outline-primary mt-auto">Login to Book</a>';
                    }
                    echo '</div></div></div>';
                }
            } else {
                echo '<p class="text-center text-muted">No packages available at the moment.</p>';
            }
        } catch (PDOException $e) {
            echo '<p class="text-danger text-center">Error loading packages: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
        ?>
    </div>
</div>

<!-- Why Choose Us -->
<section class="bg-light py-5">
    <div class="container text-center">
        <h2 class="fw-bold mb-4">✨ Why Choose TravelHub?</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-4 shadow-sm bg-white rounded">
                    <i class="bi bi-globe2 fs-1 text-primary"></i>
                    <h5 class="mt-3">Global Destinations</h5>
                    <p class="text-muted">Explore top-rated destinations around the world with curated packages.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 shadow-sm bg-white rounded">
                    <i class="bi bi-currency-dollar fs-1 text-success"></i>
                    <h5 class="mt-3">Best Price Guarantee</h5>
                    <p class="text-muted">We ensure you get the best deals without compromising on quality.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 shadow-sm bg-white rounded">
                    <i class="bi bi-headset fs-1 text-warning"></i>
                    <h5 class="mt-3">24/7 Support</h5>
                    <p class="text-muted">Our travel experts are available anytime to assist you with bookings.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="container py-5">
    <h2 class="text-center fw-bold mb-5">💬 What Our Travelers Say</h2>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 p-4">
                <p>"TravelHub made our honeymoon in Maldives unforgettable. Smooth booking and excellent service!"</p>
                <h6 class="fw-bold mb-0">– Sarah & John</h6>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 p-4">
                <p>"Best travel deals I’ve found! Easy booking process and amazing destinations."</p>
                <h6 class="fw-bold mb-0">– David R.</h6>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 p-4">
                <p>"The customer support team was fantastic! Helped us every step of the way."</p>
                <h6 class="fw-bold mb-0">– Priya S.</h6>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter CTA -->
<section class="bg-primary text-white text-center py-5">
    <div class="container">
        <h2 class="fw-bold">📩 Subscribe to Our Newsletter</h2>
        <p>Get exclusive offers and travel inspiration delivered to your inbox.</p>
        <form class="row justify-content-center">
            <div class="col-md-6">
                <input type="email" class="form-control mb-3" placeholder="Enter your email" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-light w-100">Subscribe</button>
            </div>
        </form>
    </div>
</section>

<?php include 'C:\xampp\htdocs\TravelHub\includes\footer.php'; ?>
