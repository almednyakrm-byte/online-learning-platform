**create_أساتذة.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include navigation
include 'navigation.php';

// Include form
include 'form_create_أساتذة.php';

// Include footer
include 'footer.php';
?>


**form_create_أساتذة.php**

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h2 class="text-2xl font-bold text-slate-900 mb-4">إضافة أساتذة جديد</h2>
    <form id="create-form" class="space-y-6">
        <div>
            <label for="name" class="block text-sm font-medium text-slate-900">اسم الأساتذة</label>
            <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-slate-900">بريد الإلكتروني</label>
            <input type="email" id="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div>
            <label for="phone" class="block text-sm font-medium text-slate-900">رقم الهاتف</label>
            <input type="tel" id="phone" name="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div>
            <label for="department" class="block text-sm font-medium text-slate-900">القسم</label>
            <select id="department" name="department" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">اختر القسم</option>
                <!-- Add options here -->
            </select>
        </div>
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">إضافة</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/أساتذة.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_أساتذة.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>


**backend/أساتذة.php**

<?php
// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $department = $_POST['department'];

    // Insert data into database
    $query = "INSERT INTO أساتذة (name, email, phone, department) VALUES ('$name', '$email', '$phone', '$department')";
    $result = mysqli_query($conn, $query);

    // Check if data is inserted successfully
    if ($result) {
        echo 'success';
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}
?>


Note: This code assumes that you have a database connection established and a table named "أساتذة" with columns "name", "email", "phone", and "department". You should replace the placeholder options in the select field with actual options from your database. Also, this code does not include any validation or sanitization of user input, which is a security risk. You should add proper validation and sanitization to prevent SQL injection and other security vulnerabilities.