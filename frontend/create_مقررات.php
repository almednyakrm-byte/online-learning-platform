**create_مقررات.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
require_once 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">إضافة مقرر</h2>
        <form id="create-mqrats-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">اسم المقرر</label>
                    <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">وصف المقرر</label>
                    <textarea id="description" name="description" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600"></textarea>
                </div>
            </div>
            <div>
                <label for="credits" class="block text-sm font-medium text-gray-700">الدرجات</label>
                <input type="number" id="credits" name="credits" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600">
            </div>
            <div>
                <label for="semester" class="block text-sm font-medium text-gray-700">الفصل</label>
                <select id="semester" name="semester" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600">
                    <option value="">اختر الفصل</option>
                    <option value="1">الفصل الأول</option>
                    <option value="2">الفصل الثاني</option>
                </select>
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded-lg">حفظ</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-mqrats-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/مقررات.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_مقررات.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
require_once 'footer.php';
?>


**مقررات.php (backend)**

<?php
// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Process form data
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['credits']) && isset($_POST['semester'])) {
    // Insert data into database
    $name = $_POST['name'];
    $description = $_POST['description'];
    $credits = $_POST['credits'];
    $semester = $_POST['semester'];

    // Database connection
    $conn = mysqli_connect('localhost', 'username', 'password', 'database');
    if (!$conn) {
        die('Error: ' . mysqli_connect_error());
    }

    // Insert query
    $query = "INSERT INTO مقررات (name, description, credits, semester) VALUES ('$name', '$description', '$credits', '$semester')";
    if (mysqli_query($conn, $query)) {
        echo 'success';
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }

    // Close database connection
    mysqli_close($conn);
}
?>