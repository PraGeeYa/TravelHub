<?php
session_start();
include 'db.php'; // Make sure this points to your PDO connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (!empty($name) && !empty($email) && !empty($message)) {
        try {
            $sql = "INSERT INTO feedback (name, email, message) VALUES (:name, :email, :message)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':message' => $message
            ]);

            $_SESSION['success'] = "Thank you! Your feedback has been submitted.";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "All fields are required!";
    }
}

// Redirect back to about page
header("Location: about.php");
exit;
?>
