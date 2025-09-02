<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TravelHub</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }

        /* Navbar Modern Styling */
        .navbar {
            backdrop-filter: blur(10px);
            background: rgba(0, 51, 102, 0.8) !important; /* semi-transparent navy */
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #fff !important;
        }
        .navbar-brand i {
            color: #f39c12;
            margin-right: 6px;
        }
        .nav-link {
            color: #f8f9fa !important;
            margin: 0 8px;
            position: relative;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: #f39c12 !important;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0%;
            height: 2px;
            background: #f39c12;
            left: 0;
            bottom: -4px;
            transition: width 0.3s;
        }
        .nav-link:hover::after {
            width: 100%;
        }
        .navbar-toggler {
            border: none;
        }
        .navbar-toggler:focus {
            outline: none;
            box-shadow: none;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="fas fa-plane-departure"></i> TravelHub</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="packages.php">Packages</a></li>
                    <li class="nav-item"><a class="nav-link" href="hotels_flights.php">Hotels/Flights</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="bookings.php">My Bookings</a></li>
                        <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login/Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
