**create_دورات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-8">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة دورة جديدة</h2>
        <form id="create-dorat-form">
            <div class="mb-4">
                <label for="name" class="text-slate-900 font-bold text-sm mb-2">اسم الدورة</label>
                <input type="text" id="name" name="name" class="w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="description" class="text-slate-900 font-bold text-sm mb-2">وصف الدورة</label>
                <textarea id="description" name="description" class="w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required></textarea>
            </div>
            <div class="mb-4">
                <label for="price" class="text-slate-900 font-bold text-sm mb-2">سعر الدورة</label>
                <input type="number" id="price" name="price" class="w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="duration" class="text-slate-900 font-bold text-sm mb-2">مدة الدورة</label>
                <input type="number" id="duration" name="duration" class="w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">إضافة دورة</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#create-dorat-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/دورات.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_دورات.php';
                    } else {
                        alert('Error adding course');
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**دورات.php (backend)**

<?php
// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price']) && isset($_POST['duration'])) {
    // Connect to database
    $conn = new mysqli('localhost', 'username', 'password', 'database');
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Insert data into database
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    
    $sql = "INSERT INTO دورات (name, description, price, duration) VALUES ('$name', '$description', '$price', '$duration')";
    
    if ($conn->query($sql) === TRUE) {
        echo 'success';
    } else {
        echo 'Error adding course';
    }
    
    // Close connection
    $conn->close();
}
?>