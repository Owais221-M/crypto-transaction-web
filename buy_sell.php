<?php
session_start();

// 1. Ensure user is logged in
if (!isset($_SESSION['id'])) {
    die("Error: You must be logged in to perform a transaction.");
}

// (Optional) Show errors during development
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2. Connect to the database
$servername = "localhost";
$username   = "root";
$password   = "Ansari_221";
$dbname     = "crypto_transaction";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 3. Read form data
$user_id = $_SESSION['id'];
$type    = $_POST['type'];    // "buy" or "sell"
$coin    = $_POST['coin'];    // "BTC" or "ETH"
$amount  = floatval($_POST['amount']);
$price   = floatval($_POST['price']);
$total   = $price * $amount;  // total USDT cost or proceeds

// Basic validation
if ($amount <= 0 || $price <= 0) {
    die("Invalid amount or price.");
}

// 4. Get user balances
//    Assuming 'balance' stores USDT. 
//    Optional: store 'btc_balance' and 'eth_balance' in the users table if you want to track coin holdings.
$sql = "SELECT balance, btc_balance, eth_balance FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$userResult = $stmt->get_result();
$stmt->close();

if ($userResult->num_rows === 0) {
    die("User not found in the database.");
}

$userRow = $userResult->fetch_assoc();
$currentUsdtBalance = floatval($userRow['balance']);
$currentBtc = isset($userRow['btc_balance']) ? floatval($userRow['btc_balance']) : 0;
$currentEth = isset($userRow['eth_balance']) ? floatval($userRow['eth_balance']) : 0;

// 5. Handle Buy or Sell logic
if ($type === "buy") {
    // Check if user has enough USDT to buy
    if ($currentUsdtBalance < $total) {
        die("Not enough USDT balance to buy.");
    }

    // Deduct the cost from user's USDT balance
    $newUsdtBalance = $currentUsdtBalance - $total;

    // Increase BTC or ETH balance
    if ($coin === "BTC") {
        $newBtc = $currentBtc + $amount;

        // Update the user
        $updateSql = "UPDATE users SET balance = ?, btc_balance = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ddi", $newUsdtBalance, $newBtc, $user_id);

    } else { // ETH
        $newEth = $currentEth + $amount;

        $updateSql = "UPDATE users SET balance = ?, eth_balance = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ddi", $newUsdtBalance, $newEth, $user_id);
    }
    
    $updateStmt->execute();
    $updateStmt->close();

} elseif ($type === "sell") {
    // Check if user has enough coin to sell
    if ($coin === "BTC") {
        if ($currentBtc < $amount) {
            die("Not enough BTC to sell.");
        }

        $newBtc = $currentBtc - $amount;
        $newUsdtBalance = $currentUsdtBalance + $total;

        // Update user
        $updateSql = "UPDATE users SET balance = ?, btc_balance = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ddi", $newUsdtBalance, $newBtc, $user_id);

    } else { // ETH
        if ($currentEth < $amount) {
            die("Not enough ETH to sell.");
        }

        $newEth = $currentEth - $amount;
        $newUsdtBalance = $currentUsdtBalance + $total;

        $updateSql = "UPDATE users SET balance = ?, eth_balance = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ddi", $newUsdtBalance, $newEth, $user_id);
    }

    $updateStmt->execute();
    $updateStmt->close();
} else {
    die("Invalid transaction type.");
}

// 6. Insert a transaction record
$insertSql = "INSERT INTO transactions (user_id, type, coin, amount, price, total) VALUES (?, ?, ?, ?, ?, ?)";
$insertStmt = $conn->prepare($insertSql);
$insertStmt->bind_param("issddd", $user_id, $type, $coin, $amount, $price, $total);
$insertStmt->execute();
$insertStmt->close();

$conn->close();

// 7. Redirect or display success message
echo "Transaction successful!<br>";
echo "<a href='dashboard.php'>Go back to Dashboard</a>";
