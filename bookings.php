<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Cancel (Update status) or Modify (Update date)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $id = $_POST['id'];
    if ($_POST['action'] == 'cancel') {
        $stmt = $pdo->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);
    } elseif ($_POST['action'] == 'modify') {
        $new_date = $_POST['new_date'];
        $stmt = $pdo->prepare("UPDATE bookings SET booking_date = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$new_date, $id, $_SESSION['user_id']]);
    }
}

$stmt = $pdo->prepare("SELECT b.id, b.booking_date, b.status, p.name, p.price 
                       FROM bookings b 
                       JOIN packages p ON b.package_id = p.id 
                       WHERE b.user_id = ? 
                       ORDER BY b.booking_date DESC");
$stmt->execute([$_SESSION['user_id']]);
$bookings = $stmt->fetchAll();
?>

<?php include 'includes/header.php'; ?>

<!-- Custom Styles -->
<style>
    body {
        background: #f4f7fb;
        margin: 0;
        padding-top: 80px; /* prevent navbar overlap */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .navbar {
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
        background: #1E90FF;
    }
    h1 {
        font-weight: 600;
        color: #1E90FF;
    }
    .table {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0px 3px 8px rgba(0,0,0,0.1);
    }
    .table th {
        background: #1E90FF;
        color: #fff;
        text-align: center;
    }
    .table td {
        vertical-align: middle;
        text-align: center;
    }
    .btn-sm {
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 14px;
    }
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }
    .status-active {
        background: #d1f7d1;
        color: #228B22;
    }
    .status-cancelled {
        background: #f8d7da;
        color: #b22222;
    }
</style>

<div class="container mt-5">
    <h1 class="text-center mb-4">📌 My Bookings</h1>

    <?php if (count($bookings) > 0): ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Package</th>
                        <th>Date</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
                            <td>$<?php echo number_format($booking['price'], 2); ?></td>
                            <td>
                                <span class="status-badge 
                                    <?php echo ($booking['status'] === 'cancelled') ? 'status-cancelled' : 'status-active'; ?>">
                                    <?php echo htmlspecialchars($booking['status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($booking['status'] !== 'cancelled'): ?>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="action" value="cancel">
                                        <input type="hidden" name="id" value="<?php echo $booking['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                                    </form>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="action" value="modify">
                                        <input type="hidden" name="id" value="<?php echo $booking['id']; ?>">
                                        <input type="date" name="new_date" required class="form-control form-control-sm d-inline-block" style="width: 150px;">
                                        <button type="submit" class="btn btn-warning btn-sm">Modify</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted">No actions</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-center text-muted">You have no bookings yet.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
