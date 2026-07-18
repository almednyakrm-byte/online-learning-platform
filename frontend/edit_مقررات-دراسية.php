**edit_مقررات-دراسية.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/مقررات-دراسية.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found';
    exit;
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مقررات دراسية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .slate-900 {
            color: #1a1d23;
        }
        .indigo-500 {
            color: #4b5ed5;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-lg font-bold slate-900 mb-4">تعديل مقررات دراسية</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium slate-900">اسم المقرر</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-700 rounded-md" value="<?= $existingRecord['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium slate-900">وصف المقرر</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-700 rounded-md" rows="5"><?= $existingRecord['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مقررات-دراسية.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $existingRecord['mod_slug'] ?>.php';
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


**مقررات-دراسية.php (backend)**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'ID not set'));
    exit;
}

// Get ID
$id = $_GET['id'];

// Fetch existing record details from database
// Replace with your actual database query
$existingRecord = array(
    'id' => $id,
    'name' => 'مقرر دراسي',
    'description' => 'وصف للمقرر دراسي',
    'mod_slug' => 'mod-slug'
);

echo json_encode($existingRecord);