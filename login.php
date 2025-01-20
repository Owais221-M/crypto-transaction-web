<?php
session_start();

// (Optional) Show errors during development.
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- 1. Database connection ---
    $servername = "localhost";
    $username   = "root";
    $password   = "Ansari_221";
    $dbname     = "crypto_transaction";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // --- 2. Retrieve POST data ---
    $email = $_POST['email'] ?? '';
    $pwd   = $_POST['password'] ?? '';

    // --- 3. Prepare & Execute SQL ---
    $stmt = $conn->prepare("SELECT id, email, password_hash FROM users WHERE email = ?");
    if (!$stmt) {
        die("Failed to prepare statement: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // --- 4. Check if user exists ---
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // --- 5. Verify password ---
        // Compare the plain-text password with the hashed password in the DB
        if (password_verify($pwd, $user['password_hash'])) {
            // Password is correct, set session
            $_SESSION['id'] = $user['id'];

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit; // Stop script execution after redirect
        } else {
            echo "Invalid credentials!";
        }
    } else {
        echo "No user found with that email!";
    }

    $stmt->close();
    $conn->close();

} else {
    header("Location: index.php");
    exit;
}
?>
