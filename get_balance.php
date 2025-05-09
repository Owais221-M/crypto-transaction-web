<?php
session_start();

// Basic session check
if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Database credentials
$host = 'localhost';
$user = 'root';
$password = 'password';
$dbname = 'crypto_transaction';

// Connect to MySQL
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Prepare a simple SQL statement to fetch USDT, BTC, and ETH balances
$user_id = $_SESSION['id']; 
$stmt = $conn->prepare("SELECT balance, btc_balance, eth_balance FROM users WHERE id = ?");
if ($stmt) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if we found a user record
    if ($row = $result->fetch_assoc()) {
        // Return all balances as JSON
        echo json_encode([
            'balance'      => $row['balance'],      // USDT or "fake money" balance
            'btc_balance'  => $row['btc_balance'],  // BTC holdings
            'eth_balance'  => $row['eth_balance']   // ETH holdings
        ]);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
    
    $stmt->close();
} else {
    echo json_encode(['error' => 'Database query failed']);
}

// Close the database connection
$conn->close();
?>
