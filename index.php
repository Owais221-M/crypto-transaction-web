<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crypto Platform</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="container">
        <div id="loginForm">
            <h2>Login</h2>
            <form action="login.php" method="POST">
                <input type="text" id="loginEmail" name="email" placeholder="Email" required><br>
                <input type="password" id="loginPassword" name="password" placeholder="Password" required><br>
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="#" id="showRegisterForm">Register here</a></p>
        </div>

        <div id="registerForm" style="display: none;">
            <h2>Register</h2>
            <form action="register.php" method="POST">
                <input type="text" id="registerName" name="username" placeholder="Full Name" required><br>
                <input type="text" id="registerEmail" name="email" placeholder="Email" required><br>
                <input type="password" id="registerPassword" name="password" placeholder="Password" required><br>
                <button type="submit">Register</button>
            </form>
            <p>Already have an account? <a href="#" id="showLoginForm">Login here</a></p>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>