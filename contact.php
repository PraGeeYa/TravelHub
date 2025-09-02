<?php 
session_start(); 
include 'db.php'; 
include 'includes/header.php'; 
?>

<div class="container mt-5 mb-5">

    <!-- Alerts -->
    <?php if(isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✅ Thank you! Your feedback has been submitted successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif(isset($_GET['error']) && $_GET['error'] == 1): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            ❌ Oops! Something went wrong. Please try again later.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- About Section -->
    <div class="row">
        <div class="col-md-8">
            <h1 class="mb-4">About Us</h1>
            <p class="lead">🌍 We are a trusted travel agency passionate about creating unforgettable journeys. With years of experience, we help travelers explore the world with comfort and affordability.</p>
            
            <h3 class="mt-4">Our Vision</h3>
            <p>✈️ To make travel accessible, affordable, and enjoyable for everyone.</p>
            
            <h3 class="mt-4">Our Mission</h3>
            <p>💡 To provide the best travel deals, personalized services, and memorable experiences tailored to each traveler’s needs.</p>
        </div>
        <div class="col-md-4 text-center">
            <!-- Online Travel Image -->
            <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e" 
                 class="img-fluid rounded shadow" 
                 alt="Travel Agency">
        </div>
    </div>

    <hr class="my-5">

    <!-- Why Choose Us -->
    <div class="row text-center">
        <h2 class="mb-4">Why Choose Us?</h2>
        <div class="col-md-4">
            <div class="card shadow p-3 mb-4">
                <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb" 
                     class="card-img-top rounded" alt="Destinations">
                <h4 class="mt-3">🌐 Global Destinations</h4>
                <p>Explore worldwide destinations with carefully curated packages.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow p-3 mb-4">
                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267" 
                     class="card-img-top rounded" alt="Best Deals">
                <h4 class="mt-3">💰 Best Deals</h4>
                <p>Get exclusive offers and affordable packages without hidden costs.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow p-3 mb-4">
                <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d" 
                     class="card-img-top rounded" alt="Customer Support">
                <h4 class="mt-3">🤝 Customer Support</h4>
                <p>24/7 dedicated support to guide you through your journey.</p>
            </div>
        </div>
    </div>

    <hr class="my-5">

    <!-- Feedback Section -->
    <div class="row">
        <div class="col-md-6">
            <h2>We Value Your Feedback 💬</h2>
            <p>Help us improve by sharing your thoughts and suggestions below.</p>
            <form action="feedback_process.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Your Name *</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Your Email *</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label for="message" class="form-label">Feedback *</label>
                    <textarea name="message" id="message" rows="4" class="form-control" required></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Submit Feedback</button>
            </form>
        </div>

        <!-- Display Feedback -->
        <div class="col-md-6">
            <h2>What Our Customers Say 🧳</h2>
            <?php
            $query = "SELECT name, message, created_at FROM feedback ORDER BY created_at DESC LIMIT 5";
            $result = mysqli_query($conn, $query);
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    echo '<div class="card shadow-sm p-3 mb-3">';
                    echo '<h5>'.htmlspecialchars($row['name']).'</h5>';
                    echo '<p>'.htmlspecialchars($row['message']).'</p>';
                    echo '<small class="text-muted">'.date("F j, Y", strtotime($row['created_at'])).'</small>';
                    echo '</div>';
                }
            } else {
                echo "<p>No feedback yet. Be the first to share your thoughts!</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
