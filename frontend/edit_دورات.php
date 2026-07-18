**edit_دورات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/دورات.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data is available
if ($data) {
    $title = $data['title'];
    $description = $data['description'];
} else {
    echo 'Error fetching data';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit دورات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Edit دورات</h2>
    <form id="edit-form" class="space-y-4">
        <div>
            <label for="title" class="block text-sm font-medium text-slate-900">Title</label>
            <input type="text" id="title" name="title" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg" value="<?= $title ?>">
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
            <textarea id="description" name="description" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg" rows="4"><?= $description ?></textarea>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#edit-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'PUT',
                url: '../backend/دورات.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                    } else {
                        alert('Error updating record');
                    }
                }
            });
        });
    });
</script>

</body>
</html>


**backend/دورات.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo 'Error: ID not set';
    exit;
}

// Get ID
$id = $_GET['id'];

// Check if ID is numeric
if (!is_numeric($id)) {
    echo 'Error: ID is not numeric';
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get existing record details
$query = "SELECT * FROM دورات WHERE id = '$id'";
$result = $conn->query($query);

// Check if record exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo 'Error: Record not found';
}

// Close connection
$conn->close();
?>