document.addEventListener('DOMContentLoaded', () => {
    const coinSelect = document.getElementById('coin');
    const amountInput = document.getElementById('amount');
    const displayPrice = document.getElementById('displayPrice');
    const hiddenPrice = document.getElementById('price');
    const totalDisplay = document.getElementById('totalDisplay');
    const typeInputs = document.getElementsByName('type');

    // Fetch price when the page loads
    updatePrice();

    // Also fetch price again whenever user changes the coin
    coinSelect.addEventListener('change', updatePrice);

    // Recalculate total whenever the user changes amount or type
    amountInput.addEventListener('input', updateTotal);
    typeInputs.forEach(input => {
        input.addEventListener('change', updateTotal);
    });

    // Optional: refresh price every 10 seconds
    setInterval(updatePrice, 10000);

    async function updatePrice() {
        const coin = coinSelect.value; // "BTC" or "ETH"
        const symbol = coin + "USDT";  // e.g. "BTCUSDT"

        try {
            const response = await fetch(`https://api.binance.com/api/v3/ticker/price?symbol=${symbol}`);
            const data = await response.json();

            if (data.price) {
                // Update both the read-only display and the hidden field
                displayPrice.value = parseFloat(data.price).toFixed(2);
                hiddenPrice.value = parseFloat(data.price).toFixed(2);
            } else {
                displayPrice.value = "Error";
                hiddenPrice.value = 0;
            }
        } catch (error) {
            console.error("Error fetching price:", error);
            displayPrice.value = "Error";
            hiddenPrice.value = 0;
        }

        // Recalculate total after updating price
        updateTotal();
    }

    function updateTotal() {
        const priceVal = parseFloat(hiddenPrice.value) || 0;
        const amountVal = parseFloat(amountInput.value) || 0;
        
        // For a simple buy/sell, total is just price * amount.
        // If type is "buy", total is cost. If "sell", total is proceeds.
        const total = priceVal * amountVal;
        
        totalDisplay.value = total.toFixed(2);
    }
});
