<?php
session_start();
include 'C:\xampp\htdocs\TravelHub\db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING) ?? null;
    $dob = $_POST['dob'] ?? null;
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING) ?? null;

    // Handle profile image upload
    $profile_image = null;
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['profile_image']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
                $profile_image = $target_file;
            }
        }
    }

    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);
        if ($stmt->fetch()) {
            $error = "Username or email already exists.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, name, dob, address, profile_image, role) VALUES (?, ?, ?, ?, ?, ?, ?, 'user')");
            $stmt->execute([$username, $email, $password, $name, $dob, $address, $profile_image]);
            header("Location: login.php?registered=true");
            exit();
        }
    } catch (PDOException $e) {
        $error = "Registration failed: " . htmlspecialchars($e->getMessage());
    }
}
?>
<?php include 'C:\xampp\htdocs\TravelHub\includes\header.php'; ?>

<style>
    body {
        background: linear-gradient(135deg, #36d1dc, #5b86e5);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .register-container {
        margin-top: 80px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .register-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        padding: 40px;
        width: 100%;
        max-width: 450px;
        animation: fadeIn 0.6s ease-in-out;
    }
    .register-card h2 {
        text-align: center;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 25px;
    }
    .form-control {
        border-radius: 10px;
        padding: 12px 15px;
        font-size: 16px;
    }
    .btn-register {
        width: 100%;
        background: linear-gradient(135deg, #36d1dc, #5b86e5);
        border: none;
        border-radius: 12px;
        padding: 14px;
        font-size: 18px;
        font-weight: 600;
        color: #fff;
        transition: all 0.3s ease;
    }
    .btn-register:hover {
        background: linear-gradient(135deg, #5b86e5, #36d1dc);
        transform: translateY(-2px);
    }
    .form-label {
        font-weight: 600;
        color: #34495e;
    }
    .login-link {
        text-align: center;
        margin-top: 15px;
    }
    .login-link a {
        color: #5b86e5;
        font-weight: 600;
        text-decoration: none;
    }
    .login-link a:hover {
        text-decoration: underline;
    }
    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(20px);}
        to {opacity: 1; transform: translateY(0);}
    }
</style>

<div class="container register-container">
    <div class="register-card">
        <h2><i class="fas fa-user-plus"></i> Register</h2>

        <?php if (isset($error)) echo '<div class="alert alert-danger" role="alert">' . $error . '</div>'; ?>
        <?php if (isset($_GET['registered']) && $_GET['registered'] == 'true') echo '<div class="alert alert-success" role="alert">Registration successful! Please log in.</div>'; ?>

        <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="username" class="form-label"><i class="fas fa-user"></i> Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
                <div class="invalid-feedback">Please enter a username.</div>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label"><i class="fas fa-user-tag"></i> Full Name</label>
                <input type="text" class="form-control" id="name" name="name">
            </div>
            <div class="mb-3">
                <label for="dob" class="form-label"><i class="fas fa-calendar"></i> Date of Birth</label>
                <input type="date" class="form-control" id="dob" name="dob">
            </div>
            <div class="mb-3">
                <label for="address" class="form-label"><i class="fas fa-map-marker"></i> Address</label>
                <textarea class="form-control" id="address" name="address"></textarea>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <div class="invalid-feedback">Please enter a valid email.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label"><i class="fas fa-lock"></i> Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <div class="invalid-feedback">Please enter a password.</div>
            </div>
            <div class="mb-3">
                <label for="profile_image" class="form-label"><i class="fas fa-image"></i> Profile Image</label>
                <input type="file" class="form-control" id="profile_image" name="profile_image">
            </div>
            <button type="submit" class="btn btn-register"><i class="fas fa-user-check"></i> Register</button>
        </form>

        <div class="login-link">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</div>

<?php include 'C:\xampp\htdocs\TravelHub\includes\footer.php'; ?>