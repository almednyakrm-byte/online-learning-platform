<?php
// Session check
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة تعليمية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12">
        <div class="flex justify-end mb-4">
            <button class="bg-orange-300 hover:bg-orange-400 text-blue-500 font-bold py-2 px-4 rounded" onclick="logout()">تسجيل الخروج</button>
        </div>
        <h1 class="text-3xl text-blue-500 font-bold mb-4">مرحباً!</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded shadow-md p-4 glassmorphism">
                <h2 class="text-blue-500 font-bold mb-2">إجمالي الدورات</h2>
                <p id="total-courses" class="text-3xl font-bold"></p>
            </div>
            <div class="bg-white rounded shadow-md p-4 glassmorphism">
                <h2 class="text-blue-500 font-bold mb-2">إجمالي الطلاب</h2>
                <p id="total-students" class="text-3xl font-bold"></p>
            </div>
            <div class="bg-white rounded shadow-md p-4 glassmorphism">
                <h2 class="text-blue-500 font-bold mb-2">إجمالي المدرسين</h2>
                <p id="total-instructors" class="text-3xl font-bold"></p>
            </div>
            <div class="bg-white rounded shadow-md p-4 glassmorphism">
                <h2 class="text-blue-500 font-bold mb-2">إجمالي الاختبارات</h2>
                <p id="total-exams" class="text-3xl font-bold"></p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
            <a href="courses.php" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                إدارة الدورات
            </a>
            <a href="students.php" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                إدارة الطلاب
            </a>
            <a href="instructors.php" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                إدارة المدرسين
            </a>
        </div>
    </div>

    <script>
        // Fetch stats dynamically via Javascript API calls
        fetch('api/stats.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-courses').innerText = data.totalCourses;
                document.getElementById('total-students').innerText = data.totalStudents;
                document.getElementById('total-instructors').innerText = data.totalInstructors;
                document.getElementById('total-exams').innerText = data.totalExams;
            });

        // Logout function
        function logout() {
            fetch('api/logout.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'login.php';
                    }
                });
        }
    </script>

    <style>
        .glassmorphism {
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</body>
</html>