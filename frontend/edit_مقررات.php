**edit_مقررات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$url = '../backend/مقررات.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
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
    <title>Edit مقررات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h1 class="text-lg font-bold text-emerald-600 mb-4">Edit مقررات</h1>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" class="block w-full p-2 pl-10 text-sm text-gray-700 border-gray-300 rounded-md" value="<?= $title ?>">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-700 border-gray-300 rounded-md" rows="4"><?= $description ?></textarea>
            </div>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-md">Update</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مقررات.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_مقررات.php';
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


**مقررات.php (backend)**

<?php
// Check if ID is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Fetch existing record details
    $query = "SELECT * FROM مقررات WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
    echo json_encode($data);
} else {
    // Handle invalid ID
    echo json_encode(array('error' => 'Invalid ID'));
}

// Update record
if (isset($_POST['title']) && isset($_POST['description'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $query = "UPDATE مقررات SET title = '$title', description = '$description' WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('error' => 'Error updating record'));
    }
}
?>