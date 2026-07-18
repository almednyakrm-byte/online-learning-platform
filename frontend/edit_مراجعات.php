**edit_مراجعات.php**

<?php
// Session validation
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = json_decode(file_get_contents('../backend/مراجعات.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مراجعة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-slate-900 mb-4">تعديل مراجعة</h2>
        <form id="edit-review-form">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-slate-900">العنوان</label>
                <input type="text" id="title" name="title" class="block w-full p-2 text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['title'] ?>">
            </div>
            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-slate-900">المحتوى</label>
                <textarea id="content" name="content" class="block w-full p-2 text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" rows="5"><?= $existingRecord['content'] ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">تعديل</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-review-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مراجعات.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_مراجعات.php';
                        } else {
                            alert('Error: ' + response.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/مراجعات.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

// Get ID
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = array(
    'id' => $id,
    'title' => 'مراجعة جديدة',
    'content' => 'محتوى المراجعة الجديد'
);

// Return JSON response
echo json_encode($existingRecord);