<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$booking_id = $_GET['booking_id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Simulate payment
    $stmt = $pdo->prepare("UPDATE bookings SET payment_status = 'paid', status = 'confirmed' WHERE id = ? AND user_id = ?");
    $stmt->execute([$booking_id, $_SESSION['user_id']]);
    header('Location: bookings.php');
}
?>
<?php include 'includes/header.php'; ?>
<div class="container mt-5">
    <h1>Secure Payment</h1>
    <p>Enter card details (simulated – no real charge).</p>
    <form method="POST">
        <div class="mb-3"><input type="text" class="form-control" placeholder="Card Number"></div>
        <div class="mb-3"><input type="text" class="form-control" placeholder="Expiry"></div>
        <div class="mb-3"><input type="text" class="form-control" placeholder="CVV"></div>
        <button type="submit" class="btn btn-success">Pay Now</button>
    </form>
</div>
<?php include 'includes/footer.php'; ?>