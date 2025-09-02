<?php
session_start();
include 'db.php';

// Admin CRUD Actions
if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
        if ($_POST['action'] == 'create') {
            $stmt = $pdo->prepare("INSERT INTO packages (name, description, price, itinerary, photo_url) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['name'], $_POST['description'], $_POST['price'], $_POST['itinerary'], $_POST['photo_url']]);
        } elseif ($_POST['action'] == 'update') {
            $stmt = $pdo->prepare("UPDATE packages SET name=?, description=?, price=?, itinerary=?, photo_url=? WHERE id=?");
            $stmt->execute([$_POST['name'], $_POST['description'], $_POST['price'], $_POST['itinerary'], $_POST['photo_url'], $_POST['id']]);
        } elseif ($_POST['action'] == 'delete') {
            $stmt = $pdo->prepare("DELETE FROM packages WHERE id=?");
            $stmt->execute([$_POST['id']]);
        }
    }
}
?>
<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero d-flex align-items-center justify-content-center text-center text-white">
    <div class="overlay"></div>
    <div class="content">
        <h1 class="display-3 fw-bold mb-3 animate-fade">🌍 Discover Your Next Adventure</h1>
        <p class="lead mb-4 animate-fade-delay">Exclusive travel packages tailored for unforgettable experiences.</p>
        <a href="#packages" class="btn btn-lg btn-warning fw-bold shadow-lg px-5 py-3">Explore Packages</a>
    </div>
</section>

<!-- Featured Packages -->
<div class="container my-5">
    <h2 class="text-center text-gradient fw-bold mb-4">✨ Featured Escapes</h2>
    <div class="row g-4">
        <!-- Paris -->
        <div class="col-md-6">
            <div class="featured-card" style="background-image:url('https://images.unsplash.com/photo-1472214103451-9374bd1c798e?auto=format&fit=crop&w=1350&q=80');">
                <div class="overlay"></div>
                <div class="info">
                    <h3>Paris Getaway</h3>
                    <p>Explore the City of Love - From $1200</p>
                    <a href="booking.php?package_id=1" class="btn btn-outline-light">Book Now</a>
                </div>
            </div>
        </div>
        <!-- Maldives -->
        <div class="col-md-6">
            <div class="featured-card" style="background-image:url('https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&w=1350&q=80');">
                <div class="overlay"></div>
                <div class="info">
                    <h3>Maldives Escape</h3>
                    <p>Relax on Tropical Beaches - From $2000</p>
                    <a href="booking.php?package_id=2" class="btn btn-outline-light">Book Now</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- All Packages -->
<div class="container my-5" id="packages">
    <h2 class="text-center text-gradient fw-bold mb-5">🌟 All Travel Packages</h2>
    <div class="row g-4">
        <?php
        $stmt = $pdo->query("SELECT * FROM packages");
        while ($package = $stmt->fetch()) {
            $image_url = !empty($package['photo_url']) && filter_var($package['photo_url'], FILTER_VALIDATE_URL) 
                ? htmlspecialchars($package['photo_url']) 
                : 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=600&q=80';

            echo '<div class="col-md-4">
                <div class="card package-card shadow-lg h-100 border-0">
                    <div class="img-wrapper">
                        <img src="'.$image_url.'" class="card-img-top" alt="'.htmlspecialchars($package['name']).'">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-primary">'.htmlspecialchars($package['name']).'</h5>
                        <p class="card-text flex-grow-1">'.htmlspecialchars($package['description']).'</p>
                        <p class="small text-muted"><b>Itinerary:</b> '.htmlspecialchars($package['itinerary']).'</p>
                        <p class="text-success fw-bold fs-5 mb-3">💲'.number_format($package['price'], 2).'</p>';
            
            if (isset($_SESSION['user_id'])) {
                echo '<a href="booking.php?package_id='.$package['id'].'" class="btn btn-primary mt-auto">Book Now</a>';
            } else {
                echo '<a href="login.php" class="btn btn-outline-primary mt-auto">Login to Book</a>';
            }

            if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
                echo '<form method="POST" class="mt-2">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="'.$package['id'].'">
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>';
            }

            echo '</div></div></div>';
        }
        ?>
    </div>
</div>

<!-- Admin Add Package -->
<?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
<div class="container my-5">
    <div class="card shadow-lg border-0">
        <div class="card-body">
            <h2 class="mb-4 text-center text-gradient fw-bold">➕ Add New Package</h2>
            <form method="POST" class="row g-3">
                <input type="hidden" name="action" value="create">
                <div class="col-md-6"><input type="text" name="name" class="form-control" placeholder="Package Name" required></div>
                <div class="col-md-6"><input type="number" name="price" class="form-control" placeholder="Price" step="0.01" required></div>
                <div class="col-12"><textarea name="description" class="form-control" placeholder="Description" required></textarea></div>
                <div class="col-12"><textarea name="itinerary" class="form-control" placeholder="Itinerary"></textarea></div>
                <div class="col-12"><input type="url" name="photo_url" class="form-control" placeholder="Photo URL" required></div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-success px-5">Add Package</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Custom Styles -->
<style>
/* Hero Section */
.hero {
    height: 80vh;
    background: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1600&q=80') center/cover no-repeat;
    position: relative;
}
.hero .overlay {
    position: absolute; inset: 0;
    background: rgba(0,0,0,0.5);
}
.hero .content { position: relative; z-index: 2; }
.animate-fade { animation: fadeInUp 1s ease-in-out; }
.animate-fade-delay { animation: fadeInUp 1.5s ease-in-out; }

/* Featured Cards */
.featured-card {
    position: relative;
    height: 300px;
    border-radius: 15px;
    background-size: cover;
    background-position: center;
    overflow: hidden;
    display: flex;
    align-items: flex-end;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    transition: transform .3s;
}
.featured-card:hover { transform: scale(1.03); }
.featured-card .overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
}
.featured-card .info {
    position: relative;
    z-index: 2;
    padding: 20px;
    color: #fff;
}

/* Package Cards */
.package-card {
    border-radius: 15px;
    overflow: hidden;
    transition: transform .3s;
}
.package-card:hover { transform: translateY(-8px); }
.package-card .img-wrapper {
    overflow: hidden;
}
.package-card img {
    transition: transform .4s;
}
.package-card:hover img {
    transform: scale(1.1);
}

/* Gradient Text */
.text-gradient {
    background: linear-gradient(45deg, #0072ff, #00c6ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Animations */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<?php include 'includes/footer.php'; ?>
