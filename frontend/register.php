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
<body class="h-screen bg-blue-500 flex justify-center items-center">
    <div class="bg-white p-10 rounded shadow-md w-1/2">
        <h2 class="text-orange-300 text-3xl font-bold mb-4">Register</h2>
        <form id="register-form">
            <div class="mb-4">
                <label for="username" class="block text-gray-700 font-bold mb-2">Username</label>
                <input type="text" id="username" name="username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required class="block w-full p-2 border border-gray-400 rounded">
                <div class="text-red-500 text-xs" id="username-error"></div>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" required class="block w-full p-2 border border-gray-400 rounded">
                <div class="text-red-500 text-xs" id="email-error"></div>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" required class="block w-full p-2 border border-gray-400 rounded">
                <div class="text-red-500 text-xs" id="password-error"></div>
            </div>
            <button type="submit" class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded">Register</button>
        </form>
        <div class="text-green-500 text-xs" id="success-message"></div>
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
                    $('#username-error').html('Please fill in all fields');
                    $('#email-error').html('Please fill in all fields');
                    $('#password-error').html('Please fill in all fields');
                } else if (!username.match(/[A-Za-z\u0600-\u06FF0-9\s]+/)) {
                    $('#username-error').html('Invalid username');
                    $('#email-error').html('');
                    $('#password-error').html('');
                } else if (!email.match(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/)) {
                    $('#email-error').html('Invalid email');
                    $('#username-error').html('');
                    $('#password-error').html('');
                } else {
                    $.ajax({
                        type: 'POST',
                        url: '../backend/auth.php?action=register',
                        data: {
                            username: username,
                            email: email,
                            password: password
                        },
                        success: function(response) {
                            if (response === 'success') {
                                $('#success-message').html('Registration successful');
                                $('#username-error').html('');
                                $('#email-error').html('');
                                $('#password-error').html('');
                            } else {
                                $('#success-message').html('Registration failed');
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>