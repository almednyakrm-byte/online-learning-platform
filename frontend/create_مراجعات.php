**create_مراجعات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $rating = trim($_POST['rating']);

    // Check if fields are not empty
    if (!empty($name) && !empty($description) && !empty($rating)) {
        // Insert data into database
        $sql = "INSERT INTO مراجعات (name, description, rating) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $description, $rating);
        $stmt->execute();

        // Redirect back to list page
        header('Location: list_مراجعات.php');
        exit;
    } else {
        $error = 'Please fill in all fields';
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة مراجعة جديدة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 10px;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group textarea {
            height: 100px;
        }
        .submit-btn {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #444;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>إضافة مراجعة جديدة</h2>
        </div>
        <form id="create-review-form" method="post">
            <div class="form-group">
                <label for="name">اسم المراجعة:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">وصف المراجعة:</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="rating">درجة المراجعة:</label>
                <input type="number" id="rating" name="rating" required>
            </div>
            <button type="submit" class="submit-btn" name="submit">إضافة المراجعة</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-review-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: '../backend/مراجعات.php',
                    data: formData,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_مراجعات.php';
                        } else {
                            alert('Error: ' + response);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

**مراجعات.php (backend)**

<?php
// Include database connection
require_once '../config/database.php';

// Check if form data has been submitted
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['rating'])) {
    // Insert data into database
    $sql = "INSERT INTO مراجعات (name, description, rating) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $_POST['name'], $_POST['description'], $_POST['rating']);
    $stmt->execute();

    // Return success message
    echo 'success';
} else {
    // Return error message
    echo 'Error: Invalid request';
}
?>

Note: Make sure to replace `../config/database.php` with the actual path to your database connection file. Also, make sure to adjust the SQL queries and table names according to your database schema.