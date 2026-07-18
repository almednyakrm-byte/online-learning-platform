<?php
// Initialize session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body class="h-screen bg-blue-500 flex justify-center items-center">
    <div class="glassmorphic-card bg-white/20 backdrop-blur-md rounded-3xl p-10 w-96">
        <h1 class="text-3xl font-bold text-orange-300 mb-4">Login</h1>
        <form id="login-form">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" required pattern="[A-Za-z\u0600-\u06FF0-9\s]+" class="mt-1 focus:ring-orange-300 focus:border-orange-300 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" required class="mt-1 focus:ring-orange-300 focus:border-orange-300 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <button type="submit" class="py-2 px-4 bg-orange-300 text-white rounded-md hover:bg-orange-400">Login</button>
        </form>
        <p class="mt-4">Don't have an account? <a href="register.php" class="text-orange-300 hover:text-orange-400">Register</a></p>
    </div>

    <script>
        const loginForm = document.getElementById('login-form');

        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch('../backend/auth.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });

                const data = await response.json();

                if (data.success) {
                    alert('Login successful!');
                    // Redirect to dashboard or home page
                    window.location.href = 'dashboard.php';
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        });
    </script>
</body>
</html>