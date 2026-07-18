**create_نتائج.php**

<?php
// Session validation
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12">
    <div class="flex justify-between mb-4">
        <h1 class="text-2xl font-bold text-emerald-600">إضافة نتائج جديدة</h1>
        <a href="list_نتائج.php" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded"> قائمة نتائج </a>
    </div>

    <form id="create-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="title">العنوان</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="title" type="text" placeholder="عنوان النتيجة">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">الوصف</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" placeholder="وصف النتيجة"></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="date">التاريخ</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="date" type="date">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="status">الحالة</label>
            <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="status">
                <option value="">اختر حالة النتيجة</option>
                <option value="active">نشط</option>
                <option value="inactive">غير نشط</option>
            </select>
        </div>

        <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">حفظ</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/نتائج.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_نتائج.php';
                    } else {
                        alert('حدث خطأ أثناء الحفظ');
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

**backend/نتائج.php**

<?php
// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Extract form data
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $status = $_POST['status'];

    // Insert data into database
    $query = "INSERT INTO نتائج (title, description, date, status) VALUES ('$title', '$description', '$date', '$status')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>