document.addEventListener('DOMContentLoaded', () => {
    let btcChart, ethChart;

    // ------------------------------
    // 1. Fetch and Display Balances
    // ------------------------------
    async function fetchBalance() {
        try {
            const response = await fetch('get_balance.php');
            const data = await response.json();

            // If the data includes 'balance', 'btc_balance', 'eth_balance'
            if (data.balance !== undefined) {
                document.getElementById('usdt-amount').textContent = 
                    parseFloat(data.balance).toFixed(2) + " USDT";
                document.getElementById('btc-amount').textContent = 
                    parseFloat(data.btc_balance).toFixed(6) + " BTC";
                document.getElementById('eth-amount').textContent = 
                    parseFloat(data.eth_balance).toFixed(6) + " ETH";
            } else if (data.error) {
                console.error(data.error);
            }
        } catch (error) {
            console.error('Error fetching balances:', error);
        }
    }

    // ---------------------------
    // 2. Fetch and Update Prices
    // ---------------------------
    async function updatePrices() {
        try {
            // Fetch data for BTC and ETH from Binance API
            const btcResponse = await fetch('https://api.binance.com/api/v3/ticker/24hr?symbol=BTCUSDT');
            const ethResponse = await fetch('https://api.binance.com/api/v3/ticker/24hr?symbol=ETHUSDT');
            
            const btcData = await btcResponse.json();
            const ethData = await ethResponse.json();

            // Update the prices on the dashboard
            if (btcData && ethData) {
                document.getElementById('btc-price').textContent = 
                    `$${parseFloat(btcData.lastPrice).toFixed(2)}`;
                document.getElementById('eth-price').textContent = 
                    `$${parseFloat(ethData.lastPrice).toFixed(2)}`;

                // Update market data table
                const marketData = document.getElementById('market-data');
                marketData.innerHTML = `
                    <tr>
                        <td>BTC</td>
                        <td>$${parseFloat(btcData.lastPrice).toFixed(2)}</td>
                        <td>$${parseFloat(btcData.highPrice).toFixed(2)}</td>
                        <td>$${parseFloat(btcData.lowPrice).toFixed(2)}</td>
                        <td>${parseFloat(btcData.volume).toFixed(2)} BTC</td>
                    </tr>
                    <tr>
                        <td>ETH</td>
                        <td>$${parseFloat(ethData.lastPrice).toFixed(2)}</td>
                        <td>$${parseFloat(ethData.highPrice).toFixed(2)}</td>
                        <td>$${parseFloat(ethData.lowPrice).toFixed(2)}</td>
                        <td>${parseFloat(ethData.volume).toFixed(2)} ETH</td>
                    </tr>
                `;
            }
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    }

    // ---------------------------------------------
    // 3. Show/Hide Charts and Initialize if Needed
    // ---------------------------------------------
    document.getElementById('btc-link').addEventListener('click', (e) => {
        e.preventDefault();
        showChart('btc');
    });
    document.getElementById('eth-link').addEventListener('click', (e) => {
        e.preventDefault();
        showChart('eth');
    });

    function showChart(crypto) {
        // Hide both charts
        document.getElementById('btc-chart').style.display = 'none';
        document.getElementById('eth-chart').style.display = 'none';

        // Show the selected chart
        if (crypto === 'btc') {
            document.getElementById('btc-chart').style.display = 'block';
            if (!btcChart) {
                createChart('btc');
            }
        } else {
            document.getElementById('eth-chart').style.display = 'block';
            if (!ethChart) {
                createChart('eth');
            }
        }
    }

    function createChart(crypto) {
        const ctx = document.getElementById(
            crypto === 'btc' ? 'btcChart' : 'ethChart'
        ).getContext('2d');

        const chartData = {
            labels: [],
            datasets: [
                {
                    label: `${crypto.toUpperCase()}/USDT Price`,
                    data: [],
                    borderColor: 'rgba(240, 185, 11, 1)', // Binance gold
                    backgroundColor: 'rgba(240, 185, 11, 0.2)',
                    fill: true,
                    tension: 0.1
                }
            ]
        };

        const chartOptions = {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Time'
                    },
                    ticks: { color: '#eaeaea' }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Price (USDT)'
                    },
                    ticks: { color: '#eaeaea' }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: '#eaeaea'
                    }
                }
            }
        };

        if (crypto === 'btc') {
            btcChart = new Chart(ctx, { type: 'line', data: chartData, options: chartOptions });
            startUpdatingChartData('btc');
        } else {
            ethChart = new Chart(ctx, { type: 'line', data: chartData, options: chartOptions });
            startUpdatingChartData('eth');
        }
    }

    // -----------------------------------------------------
    // 5. Update Chart Data in Intervals for Live Prices
    // -----------------------------------------------------
    function startUpdatingChartData(crypto) {
        setInterval(() => updateChartData(crypto), 2000); // update every 5s
    }

    async function updateChartData(crypto) {
        try {
            // Binance 24hr ticker also works, but let's get the current price endpoint:
            const symbol = crypto.toUpperCase() + "USDT"; 
            const response = await fetch(
                `https://api.binance.com/api/v3/ticker/price?symbol=${symbol}`
            );
            const data = await response.json();

            const price = parseFloat(data.price);

            const currentTime = new Date().toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });

            let chartRef = crypto === 'btc' ? btcChart : ethChart;
            if (chartRef) {
                chartRef.data.labels.push(currentTime);
                chartRef.data.datasets[0].data.push(price);

                // Keep only the last 60 data points
                if (chartRef.data.labels.length > 60) {
                    chartRef.data.labels.shift();
                    chartRef.data.datasets[0].data.shift();
                }
                chartRef.update();
            }
        } catch (error) {
            console.error(`Error updating ${crypto} chart:`, error);
        }
    }

    // -------------------------------------
    // 6. On Page Load, Fetch Data & Set Timers
    // -------------------------------------
    // Immediately fetch prices & balances
    updatePrices();
    fetchBalance();

    setInterval(updatePrices, 2000);
});
