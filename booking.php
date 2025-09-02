<?php
session_start();
include 'C:\xampp\htdocs\TravelHub\db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $package_id = $_POST['package_id'];
    $booking_date = $_POST['booking_date'];

    $stmt = $pdo->prepare("INSERT INTO bookings (user_id, package_id, booking_date, status) VALUES (?, ?, ?, 'confirmed')");
    $stmt->execute([$_SESSION['user_id'], $package_id, $booking_date]);
    header('Location: bookings.php');
    exit();
}

$package_id = $_GET['package_id'];
$stmt = $pdo->prepare("SELECT * FROM packages WHERE id = ?");
$stmt->execute([$package_id]);
$package = $stmt->fetch();
?>
<?php include 'includes/header.php'; ?>
<div class="container mt-5">
    <h1>Book <?php echo htmlspecialchars($package['name']); ?></h1>
    <form method="POST">
        <input type="hidden" name="package_id" value="<?php echo htmlspecialchars($package_id); ?>">
        <div class="mb-3">
            <label for="booking_date" class="form-label">Select Booking Date</label>
            <input type="date" name="booking_date" class="form-control" id="booking_date" required>
            <div class="invalid-feedback">Please select a booking date.</div>
        </div>
        <button type="submit" class="btn btn-primary w-100">Confirm Booking</button>
    </form>
</div>
<?php include 'includes/footer.php'; ?>