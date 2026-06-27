<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة تعليمية إلكترونية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-slate-900 text-white">
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold">منصة تعليمية إلكترونية</h1>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold mb-2">مرحباً <?= $_SESSION['username'] ?></h2>
            <p>منصة تعليمية إلكترونية للتعلم عن بعد</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
            <?php
            // Fetch stats dynamically via Javascript API calls from the backend files
            $stats = json_decode(file_get_contents('https://example.com/api/stats'), true);
            ?>
            <div class="glassmorphism-card p-4">
                <h2 class="text-2xl font-bold mb-2">طلاب</h2>
                <p><?= $stats['students'] ?></p>
            </div>
            <div class="glassmorphism-card p-4">
                <h2 class="text-2xl font-bold mb-2">معلمين</h2>
                <p><?= $stats['teachers'] ?></p>
            </div>
            <div class="glassmorphism-card p-4">
                <h2 class="text-2xl font-bold mb-2">دورات</h2>
                <p><?= $stats['courses'] ?></p>
            </div>
            <div class="glassmorphism-card p-4">
                <h2 class="text-2xl font-bold mb-2">اختبارات</h2>
                <p><?= $stats['exams'] ?></p>
            </div>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold mb-2">إدارة الدورات</h2>
            <div class="flex justify-between items-center mb-2">
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='students.php'">طلاب</button>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='teachers.php'">معلمين</button>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='courses.php'">دورات</button>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='exams.php'">اختبارات</button>
            </div>
        </div>
    </div>
    <script>
        // Fetch stats dynamically via Javascript API calls from the backend files
        fetch('https://example.com/api/stats')
            .then(response => response.json())
            .then(stats => {
                document.getElementById('students').innerHTML = stats.students;
                document.getElementById('teachers').innerHTML = stats.teachers;
                document.getElementById('courses').innerHTML = stats.courses;
                document.getElementById('exams').innerHTML = stats.exams;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code uses Tailwind CSS for styling and fetches stats dynamically via Javascript API calls from the backend files. It also includes a session check to redirect to the login page if the user is not authenticated. The dashboard layout includes a welcome message, logout button, overview stats grid, and quick links to manage modules. The color palette used is slate-900 and indigo-500.