**create_اختبارات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Create new record
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $date = $_POST['date'];

    // Validate input
    if (empty($name) || empty($description) || empty($date)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert new record
        $sql = "INSERT INTO اختبارات (name, description, date) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $description, $date]);

        // Redirect back to list page
        header('Location: list_اختبارات.php');
        exit;
    }
}

// Include header
require_once '../backend/header.php';

?>

<!-- Create new record form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Create New اختبارات</h2>
    <form id="create-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-slate-900">Description:</label>
            <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
        </div>
        <div class="mb-4">
            <label for="date" class="block text-sm font-medium text-slate-900">Date:</label>
            <input type="date" id="date" name="date" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create</button>
    </form>
    <?php if (isset($error)) : ?>
        <p class="text-red-500 mt-2"><?= $error ?></p>
    <?php endif; ?>
</div>

<!-- Include footer -->
<?php require_once '../backend/footer.php'; ?>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/اختبارات.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_اختبارات.php';
                    } else {
                        alert('Error creating new record');
                    }
                }
            });
        });
    });
</script>

**backend/اختبارات.php**

<?php
// Include database connection
require_once '../db.php';

// Check if form data was sent
if (isset($_POST['submit'])) {
    // Insert new record
    $sql = "INSERT INTO اختبارات (name, description, date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_POST['name'], $_POST['description'], $_POST['date']]);

    // Return success message
    echo 'success';
    exit;
}