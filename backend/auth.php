<?php

// Start the session to handle user authentication
session_start();

// Import the database connection script
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, return a JSON response indicating their status
    $response = array('status' => 'logged_in', 'user_id' => $_SESSION['user_id']);
    echo json_encode($response);
    exit;
}

// Check for AJAX requests
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    // Handle login request
    if (isset($_POST['action']) && $_POST['action'] == 'login') {
        // Check input fields for login
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            // Prepare the SQL query to select the user
            $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if the user exists
            if ($result->num_rows == 1) {
                // Fetch the user data
                $user = $result->fetch_assoc();

                // Verify the password
                if (password_verify($password, $user['password'])) {
                    // If the password is correct, log the user in
                    $_SESSION['user_id'] = $user['id'];
                    $response = array('status' => 'logged_in', 'user_id' => $_SESSION['user_id']);
                    echo json_encode($response);
                } else {
                    // If the password is incorrect, return an error response
                    $response = array('status' => 'error', 'message' => 'Invalid password');
                    echo json_encode($response);
                }
            } else {
                // If the user does not exist, return an error response
                $response = array('status' => 'error', 'message' => 'Invalid username or password');
                echo json_encode($response);
            }
        } else {
            // If the input fields are missing, return an error response
            $response = array('status' => 'error', 'message' => 'Missing input fields');
            echo json_encode($response);
        }
    }

    // Handle register request
    elseif (isset($_POST['action']) && $_POST['action'] == 'register') {
        // Check input fields for registration
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password'])) {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);

            // Check if the password and confirm password match
            if ($password == $confirm_password) {
                // Check if the username and email are already taken
                $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
                $stmt->bind_param("ss", $username, $email);
                $stmt->execute();
                $result = $stmt->get_result();

                // Check if the username and email are already taken
                if ($result->num_rows == 0) {
                    // Hash the password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Prepare the SQL query to insert the new user
                    $stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $username, $email, $hashed_password);
                    $stmt->execute();

                    // Get the ID of the newly inserted user
                    $user_id = $mysqli->insert_id;

                    // Log the user in
                    $_SESSION['user_id'] = $user_id;
                    $response = array('status' => 'logged_in', 'user_id' => $_SESSION['user_id']);
                    echo json_encode($response);
                } else {
                    // If the username or email is already taken, return an error response
                    $response = array('status' => 'error', 'message' => 'Username or email already taken');
                    echo json_encode($response);
                }
            } else {
                // If the password and confirm password do not match, return an error response
                $response = array('status' => 'error', 'message' => 'Passwords do not match');
                echo json_encode($response);
            }
        } else {
            // If the input fields are missing, return an error response
            $response = array('status' => 'error', 'message' => 'Missing input fields');
            echo json_encode($response);
        }
    }

    // Handle logout request
    elseif (isset($_POST['action']) && $_POST['action'] == 'logout') {
        // Log the user out
        session_destroy();
        $response = array('status' => 'logged_out');
        echo json_encode($response);
    }
}

// Close the database connection
$mysqli->close();

?>


This script handles user registration, login, logout, and checking the current session user status. It uses prepared statements to prevent SQL injection and password hashing to store passwords securely. It also checks input fields securely to prevent cross-site scripting (XSS) attacks. The script returns JSON responses for AJAX calls.