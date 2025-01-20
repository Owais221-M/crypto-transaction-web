
# Crypto Transaction Website

Welcome to my **Crypto Transaction Website**! This project is inspired by the look and feel of Binance and aims to provide a simple, hands-on way to explore crypto prices, balances, and buy/sell functionality—all using “fake” money for learning purposes.

## Why I Built This
I wanted to **practice** web development with a **realistic** use case—like a miniature trading platform. By integrating the **Binance API**, I could fetch live BTC/ETH prices and display them in a clean, interactive dashboard. At the same time, I was able to strengthen my **PHP/MySQL** skills by storing user balances and transactions on the backend.

## How It Works
1. **User Login**:  
   - Register or create a user directly in the database (depending on your setup).  
   - Login with your email and password. (I’m using PHP sessions to keep you logged in.)

2. **Dashboard**:  
   - Once logged in, you’ll see a **dark-themed** dashboard showing your **USDT** balance, as well as your **BTC** and **ETH** balances if you have any.  
   - Real-time **BTC/ETH** prices are fetched from the Binance API every few seconds, so you can always stay updated.  
   - You can also toggle between line charts or candlestick charts (if you choose to implement them) to get a quick market overview.

3. **Buy/Sell**:  
   - There’s a **Buy/Sell** form where you can simulate trading. When you “buy,” it deducts from your USDT balance and increases your BTC or ETH holdings. “Sell” does the opposite.  
   - Each transaction is recorded in a `transactions` table, so you can keep track of your activity.

4. **Logout**:  
   - When you’re done, you can log out, which destroys your session and sends you back to the login page.

## Tech Stack
- **Front-end**: HTML, CSS (dark theme), and JavaScript (with Chart.js for the charts).  
- **Back-end**: PHP (for sessions and database queries), MySQL (to store user info, balances, and transactions).  
- **API**: Binance (for live BTC/ETH prices).

## Setting It Up
1. **Clone or Download** this project into your local server environment (like XAMPP’s `htdocs` folder).  
2. **Database Setup**:  
   - Create a database (e.g., `crypto_transaction`) in MySQL.  
   - Import or run the SQL scripts to create a `users` table (with fields like `id`, `email`, `password_hash`, `balance`, `btc_balance`, `eth_balance`) and a `transactions` table (with fields like `user_id`, `type`, `coin`, `amount`, `price`, `total`, etc.).  
3. **Credentials**:  
   - Open the PHP files (`login.php`, `buy_sell.php`, `get_balance.php`, etc.) and match the `$servername`, `$username`, `$password`, and `$dbname` variables with your own setup.  
4. **Usage**:  
   - Go to `login.php` in your browser.  
   - Log in (or register if you have a registration form).  
   - Explore the dashboard, check balances, and try some buy/sell trades.

## Notes & Future Ideas
- **Password Hashing**: I used `password_hash()`/`password_verify()` so we’re not storing plain passwords.  
- **Fake Money**: The balances and trades are purely for demonstration; it’s not intended for real-world crypto trading.  
- **Candlestick Charts**: If you want to use candlestick charts, add the `chartjs-chart-financial` plugin and fetch kline data from Binance.  
- **More Coins**: It’s easy to extend this to other coins by duplicating the BTC/ETH logic for LTC, XRP, or any other Binance-supported symbol.

## Credits
- **Binance**: For the free API access to get real-time crypto data.  
- **Chart.js**: For the great charting library that makes data visualization straightforward.  
- **PHP & MySQL**: The backbone of the backend logic.

## Wrap-Up
I hope this little project gives a taste of how a crypto dashboard might work under the hood. Feel free to fork it, modify it, or extend it into something bigger. If you run into any issues or have suggestions, I’d love to hear about it!

Thanks for checking it out—happy coding, and may your trades (fake or otherwise) always go to the moon!

---  

*Enjoy exploring and customizing this project for your own learning or demo purposes!*
