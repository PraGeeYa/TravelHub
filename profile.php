<?php
session_start();
include 'C:\xampp\htdocs\TravelHub\db.php';

if (!isset($_SESSION['user_id'])) { 
    header('Location: login.php'); 
    exit; 
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// CRUD Operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        $name = $_POST['name'] ?? $user['name'];
        $dob = $_POST['dob'] ?? $user['dob'];
        $address = $_POST['address'] ?? $user['address'];
        $email = $_POST['email'] ?? $user['email'];

        // Handle profile image upload
        $profile_image = $user['profile_image'];
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
            $target_dir = "uploads/";
            $base_name = basename($_FILES['profile_image']['name']);
            $target_file = $target_dir . time() . '_' . preg_replace('/[^A-Za-z0-9\-]/', '_', $base_name); // Sanitized filename with timestamp
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($imageFileType, $allowed_types)) {
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
                    chmod($target_file, 0644); // Set read permissions
                    $profile_image = $target_file;
                    // Delete old image if it exists and different
                    if ($user['profile_image'] && file_exists($user['profile_image']) && $user['profile_image'] != $target_file) {
                        unlink($user['profile_image']);
                    }
                } else {
                    error_log("Failed to move uploaded file to $target_file. Error: " . $_FILES['profile_image']['error']);
                }
            } else {
                error_log("Invalid file type: $imageFileType");
            }
        }

        $stmt = $pdo->prepare("UPDATE users SET name = ?, dob = ?, address = ?, email = ?, profile_image = ? WHERE id = ?");
        $stmt->execute([$name, $dob, $address, $email, $profile_image, $_SESSION['user_id']]);
        header('Location: profile.php');
        exit();
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete_image') {
        if ($user['profile_image'] && file_exists($user['profile_image'])) {
            unlink($user['profile_image']);
            $stmt = $pdo->prepare("UPDATE users SET profile_image = NULL WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
        }
        header('Location: profile.php');
        exit();
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete_field') {
        $field = $_POST['field'];
        $value = null;
        if ($field == 'name') $value = $user['name'];
        elseif ($field == 'dob') $value = $user['dob'];
        elseif ($field == 'address') $value = $user['address'];
        elseif ($field == 'email') $value = $user['email'];
        $stmt = $pdo->prepare("UPDATE users SET $field = NULL WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        header('Location: profile.php');
        exit();
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete_profile' && isset($_POST['confirm_delete']) && $_POST['confirm_delete'] == 'yes') {
        // Delete profile image if it exists
        if ($user['profile_image'] && file_exists($user['profile_image'])) {
            unlink($user['profile_image']);
        }
        // Delete user record
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        // Destroy session and redirect
        session_destroy();
        header('Location: login.php?profile_deleted=true');
        exit();
    }
}
?>
<?php include 'includes/header.php'; ?>

<style>
    body {
        padding-top: 90px;
        background-color: #f5f7fa;
    }
    .profile-card {
        background: linear-gradient(135deg, #36d1dc, #5b86e5);
        border-radius: 15px;
        padding: 30px;
        color: #fff;
        text-align: center;
        box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    }
    .profile-avatar {
        width: 150px;
        height: 150px;
        background: #fff;
        color: #5b86e5;
        font-size: 60px;
        font-weight: bold;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px auto;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        overflow: hidden;
    }
    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .card-section {
        background: #fff;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .btn-update {
        background: #5b86e5;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        transition: all 0.3s ease;
    }
    .btn-update:hover {
        background: #36d1dc;
        transform: translateY(-2px);
    }
    .btn-delete {
        background: #dc3545;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 5px 10px;
        margin-left: 10px;
        transition: all 0.3s ease;
    }
    .btn-delete:hover {
        background: #c82333;
        transform: translateY(-2px);
    }
    .btn-delete-profile {
        background: #ff4444;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        margin-top: 10px;
        transition: all 0.3s ease;
    }
    .btn-delete-profile:hover {
        background: #cc0000;
        transform: translateY(-2px);
    }
    .debug {
        color: red;
        font-size: 12px;
        margin-top: 10px;
    }
    .confirmation {
        margin-top: 15px;
        display: none;
    }
    .confirmation.active {
        display: block;
    }
</style>

<div class="container">
    <div class="row">
        <!-- Profile Sidebar -->
        <div class="col-md-4 mb-4">
            <div class="profile-card">
                <div class="profile-avatar">
                    <?php
                    $image_path = $user['profile_image'] ? "http://localhost/TravelHub/" . htmlspecialchars($user['profile_image']) : '';
                    if ($user['profile_image'] && file_exists($user['profile_image'])) {
                        if (getimagesize($user['profile_image'])) {
                            echo '<img src="' . $image_path . '" alt="Profile Image">';
                        } else {
                            echo strtoupper(substr($user['username'], 0, 1));
                            echo '<div class="debug">Image file exists but is not readable or invalid.</div>';
                        }
                    } else {
                        echo strtoupper(substr($user['username'], 0, 1));
                        echo '<div class="debug">No image uploaded or path invalid: ' . htmlspecialchars($user['profile_image'] ?? 'null') . '</div>';
                    }
                    ?>
                </div>
                <h4><?php echo htmlspecialchars($user['name'] ?? $user['username']); ?></h4>
                <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($user['email']); ?></p>
                <span class="badge bg-light text-dark">User</span>
                <?php if ($user['profile_image']): ?>
                    <form method="POST" style="margin-top: 10px;">
                        <input type="hidden" name="action" value="delete_image">
                        <button type="submit" class="btn btn-delete btn-sm">Remove Image</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="col-md-8">
            <!-- Update Profile -->
            <div class="card-section">
                <h5 class="mb-3">Update Profile</h5>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>">
                        <?php if ($user['name']): ?>
                            <button type="submit" name="action" value="delete_field" formaction="profile.php" class="btn btn-delete btn-sm" formnovalidate>Remove</button>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input type="date" name="dob" class="form-control" value="<?php echo htmlspecialchars($user['dob'] ?? ''); ?>">
                        <?php if ($user['dob']): ?>
                            <button type="submit" name="action" value="delete_field" formaction="profile.php" class="btn btn-delete btn-sm" formnovalidate>Remove</button>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea name="address" class="form-control"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                        <?php if ($user['address']): ?>
                            <button type="submit" name="action" value="delete_field" formaction="profile.php" class="btn btn-delete btn-sm" formnovalidate>Remove</button>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="profile_image" class="form-label">Profile Image</label>
                        <input type="file" name="profile_image" class="form-control">
                        <?php if (isset($_FILES['profile_image'])): ?>
                            <?php if ($_FILES['profile_image']['error'] != 0): ?>
                                <div class="debug">Upload error: <?php echo $_FILES['profile_image']['error']; ?> - <?php echo $upload_error_messages[$_FILES['profile_image']['error']] ?? 'Unknown error'; ?></div>
                            <?php elseif (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file ?? '')): ?>
                                <div class="debug">Failed to move file. Check permissions or disk space.</div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-update">Save Changes</button>
                </form>
            </div>

            <!-- Delete Profile Section -->
            <div class="card-section">
                <h5 class="mb-3">Danger Zone</h5>
                <p class="text-warning">Deleting your profile will permanently remove all your data, including bookings and personal information.</p>
                <button type="button" class="btn btn-delete-profile" id="deleteProfileBtn">Delete Profile</button>
                <div class="confirmation" id="deleteConfirmation">
                    <p class="text-danger">Are you sure you want to delete your profile? This action cannot be undone.</p>
                    <form method="POST">
                        <input type="hidden" name="action" value="delete_profile">
                        <input type="hidden" name="confirm_delete" value="yes">
                        <button type="submit" class="btn btn-danger">Yes, Delete My Profile</button>
                        <button type="button" class="btn btn-secondary" id="cancelDeleteBtn">Cancel</button>
                    </form>
                </div>
            </div>

            <!-- Booking History -->
            <div class="card-section">
                <h5 class="mb-3">Booking History</h5>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Your booking history will appear here soon.
                </div>
                <p>Saved Trips: <em>Feature coming soon 🚀</em></p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<?php
// Define upload error messages for debugging
$upload_error_messages = [
    UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
    UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
    UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
    UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
    UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
    UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
];
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteBtn = document.getElementById('deleteProfileBtn');
    const cancelBtn = document.getElementById('cancelDeleteBtn');
    const confirmation = document.getElementById('deleteConfirmation');

    deleteBtn.addEventListener('click', function() {
        confirmation.classList.add('active');
    });

    cancelBtn.addEventListener('click', function() {
        confirmation.classList.remove('active');
    });
});
</script>