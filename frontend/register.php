<?php
// Initialize session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900 h-screen flex justify-center items-center">
    <div class="bg-indigo-500 p-10 rounded-lg shadow-lg w-1/2">
        <h1 class="text-3xl text-white font-bold mb-4">Register</h1>
        <form id="register-form">
            <div class="mb-4">
                <label for="username" class="text-white block mb-2">Username:</label>
                <input type="text" id="username" name="username" required pattern="[A-Za-z\u0600-\u06FF0-9\s]+" class="bg-gray-200 p-2 w-full rounded-lg">
                <span class="text-red-500" id="username-error"></span>
            </div>
            <div class="mb-4">
                <label for="email" class="text-white block mb-2">Email:</label>
                <input type="email" id="email" name="email" required class="bg-gray-200 p-2 w-full rounded-lg">
                <span class="text-red-500" id="email-error"></span>
            </div>
            <div class="mb-4">
                <label for="password" class="text-white block mb-2">Password:</label>
                <input type="password" id="password" name="password" required pattern="[A-Za-z\u0600-\u06FF0-9\s]+" class="bg-gray-200 p-2 w-full rounded-lg">
                <span class="text-red-500" id="password-error"></span>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg w-full">Register</button>
        </form>
        <div id="register-response" class="text-white mt-4"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#register-form').submit(function(e) {
                e.preventDefault();
                var username = $('#username').val();
                var email = $('#email').val();
                var password = $('#password').val();

                if (username === '' || email === '' || password === '') {
                    $('#register-response').html('Please fill in all fields.');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: '../backend/auth.php?action=register',
                    data: {
                        username: username,
                        email: email,
                        password: password
                    },
                    success: function(response) {
                        $('#register-response').html(response);
                    }
                });
            });
        });
    </script>
</body>
</html>