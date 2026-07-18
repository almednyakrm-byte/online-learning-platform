**create_مقررات-دراسية.php**

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
    $credits = trim($_POST['credits']);

    if (!empty($name) && !empty($description) && !empty($credits)) {
        // Insert data into database
        $query = "INSERT INTO مقررات_دراسية (name, description, credits) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("sss", $name, $description, $credits);
        $stmt->execute();

        // Redirect back to list page
        header('Location: list_مقررات-دراسية.php');
        exit;
    } else {
        $error = 'Please fill in all fields';
    }
}

// Include header
require_once '../includes/header.php';

// Include premium Tailwind UI form
?>

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h2 class="text-2xl font-bold text-slate-900 mb-4">Create New مقررات دراسية</h2>

    <?php if (isset($error)) : ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form action="" method="post" class="space-y-6">
        <div>
            <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
            <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
            <textarea id="description" name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
        </div>

        <div>
            <label for="credits" class="block text-sm font-medium text-slate-900">Credits</label>
            <input type="number" id="credits" name="credits" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
        </div>

        <button type="submit" name="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create</button>
    </form>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>


**create_مقررات-دراسية.js**
javascript
$(document).ready(function() {
    $('#create-form').submit(function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: '../backend/مقررات-دراسية.php',
            data: formData,
            success: function(response) {
                if (response === 'success') {
                    window.location.href = 'list_مقررات-دراسية.php';
                } else {
                    alert('Error creating new مقررات دراسية');
                }
            },
            error: function(xhr, status, error) {
                alert('Error creating new مقررات دراسية: ' + error);
            }
        });
    });
});


**Note:** Make sure to replace `../backend/مقررات-دراسية.php` with the actual URL of your backend script that handles the form submission. Also, update the `list_مقررات-دراسية.php` URL to match the actual URL of your list page.