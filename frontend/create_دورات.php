**create_دورات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $image = $_FILES['image'];

    // Check for errors
    if (empty($name) || empty($description) || empty($price) || empty($duration)) {
        $error = 'Please fill in all fields';
    } elseif (!is_numeric($price)) {
        $error = 'Price must be a number';
    } else {
        // Insert data into database
        $query = "INSERT INTO دورات (name, description, price, duration, image) VALUES ('$name', '$description', '$price', '$duration', '$image')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Redirect back to list page
            header('Location: list_دورات.php');
            exit;
        } else {
            $error = 'Error inserting data';
        }
    }
}

// Include header
require_once '../backend/header.php';

?>

<!-- Create دورات form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-slate-900 text-lg font-bold mb-4">Create دورات</h2>
    <form id="create-dorats-form" method="post" enctype="multipart/form-data">
        <div class="mb-4">
            <label for="name" class="block text-slate-900 text-sm font-bold mb-2">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-slate-900 bg-gray-100 rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-slate-900 text-sm font-bold mb-2">Description:</label>
            <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-slate-900 bg-gray-100 rounded-lg" required></textarea>
        </div>
        <div class="mb-4">
            <label for="price" class="block text-slate-900 text-sm font-bold mb-2">Price:</label>
            <input type="number" id="price" name="price" class="block w-full p-2 pl-10 text-sm text-slate-900 bg-gray-100 rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="duration" class="block text-slate-900 text-sm font-bold mb-2">Duration:</label>
            <input type="text" id="duration" name="duration" class="block w-full p-2 pl-10 text-sm text-slate-900 bg-gray-100 rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="image" class="block text-slate-900 text-sm font-bold mb-2">Image:</label>
            <input type="file" id="image" name="image" class="block w-full p-2 pl-10 text-sm text-slate-900 bg-gray-100 rounded-lg" required>
        </div>
        <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create</button>
    </form>
    <?php if (isset($error)) : ?>
        <p class="text-red-500 text-sm mt-2"><?= $error ?></p>
    <?php endif; ?>
</div>

<!-- Include footer -->
<?php require_once '../backend/footer.php'; ?>

<script>
    // AJAX form submission
    document.getElementById('create-dorats-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        fetch('../backend/دورات.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_دورات.php';
            } else {
                console.error(data.error);
            }
        })
        .catch(error => console.error(error));
    });
</script>


**backend/دورات.php**

<?php
// Include database connection
require_once '../backend/db.php';

// Check if form data has been sent
if (isset($_FILES['image'])) {
    // Validate form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $image = $_FILES['image'];

    // Check for errors
    if (empty($name) || empty($description) || empty($price) || empty($duration)) {
        echo json_encode(['success' => false, 'error' => 'Please fill in all fields']);
    } elseif (!is_numeric($price)) {
        echo json_encode(['success' => false, 'error' => 'Price must be a number']);
    } else {
        // Insert data into database
        $query = "INSERT INTO دورات (name, description, price, duration, image) VALUES ('$name', '$description', '$price', '$duration', '$image')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error inserting data']);
        }
    }
}