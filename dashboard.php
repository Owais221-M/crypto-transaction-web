<?php
// dashboard.php
session_start();

// Check if user is logged in; if not, redirect to login
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crypto Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <header>
        <h1>My Crypto Dashboard</h1>
        <a href="logout.php" class="logout-btn">Logout</a>
    </header>
    
    <main>
        <section class="crypto-container">
<div id="balance-display">
    <h2>Your Balances</h2>
    <p>USDT: <span id="usdt-amount">Loading...</span></p>
    <p>BTC : <span id="btc-amount">Loading...</span></p>
    <p>ETH : <span id="eth-amount">Loading...</span></p>
</div>
            <div style="margin-bottom: 20px;">
                <a href="buy_sell_form.html" class="trade-btn">Buy/Sell Crypto</a>
            </div>
            <div id="crypto-prices">
                <h2>Current Prices</h2>
                <div class="crypto">
                    <h3><a href="#" id="btc-link" aria-label="Show BTC Chart">BTC/USDT</a></h3>
                    <p id="btc-price">Loading...</p>
                </div>
                <div class="crypto">
                    <h3><a href="#" id="eth-link" aria-label="Show ETH Chart">ETH/USDT</a></h3>
                    <p id="eth-price">Loading...</p>
                </div>
            </div>
            <div id="crypto-table">
                <h2>Market Data (24h)</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Coin</th>
                            <th>Price</th>
                            <th>24h High</th>
                            <th>24h Low</th>
                            <th>Volume</th>
                        </tr>
                    </thead>
                    <tbody id="market-data">
                        <tr>
                            <td colspan="5">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="charts">
                <div id="btc-chart" class="chart-container" style="display:none;">
                    <h3>BTC/USDT Chart</h3>
                    <canvas id="btcChart"></canvas>
                </div>
                <div id="eth-chart" class="chart-container" style="display:none;">
                    <h3>ETH/USDT Chart</h3>
                    <canvas id="ethChart"></canvas>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>© <?php echo date("Y"); ?> My Crypto Dashboard</p>
    </footer>
    <script src="dashboard.js"></script>
</body>
</html>
