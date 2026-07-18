**create_طلاب.php**

<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit;
}

require_once '../backend/config.php';

$mod_slug = 'طلاب';

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة طالب جديد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .bg-slate-900 {
            background-color: #1a1a1a;
        }
        .text-indigo-500 {
            color: #6b6bcf;
        }
    </style>
</head>
<body class="bg-slate-900 text-indigo-500">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">إضافة طالب جديد</h1>
        <form id="create-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-lg font-bold mb-2">اسم الطالب:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-lg font-bold mb-2">بريد الإلكتروني:</label>
                <input type="email" id="email" name="email" class="block w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-lg font-bold mb-2">رقم الهاتف:</label>
                <input type="tel" id="phone" name="phone" class="block w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label for="address" class="block text-lg font-bold mb-2">العنوان:</label>
                <textarea id="address" name="address" class="block w-full p-2 border border-gray-300 rounded" required></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">حفظ</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: '../backend/طلاب.php',
                    data: formData,
                    success: function(response) {
                        if (response == 'success') {
                            window.location.href = '../list_طلاب.php';
                        } else {
                            alert('حدث خطأ أثناء الحفظ');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

**Note:** Make sure to replace `../backend/config.php` with the actual path to your backend configuration file. Also, update the `../backend/طلاب.php` file to handle the form data and insert it into the database.