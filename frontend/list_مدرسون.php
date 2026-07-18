**list_مدرسون.php**

<?php
// Session validation
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
    <title>مدرسون</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .emerald-600 {
            color: #008E77;
        }
        .teal-500 {
            color: #0097A7;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-md p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="document.location='logout.php'">تسجيل الخروج</button>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">مدرسون</h1>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="document.location='create_مدرسون.php'">إضافة جديد</button>
        <div class="flex justify-between mb-4">
            <input type="search" class="w-full p-2 mb-4" placeholder="بحث" id="search">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border border-gray-400 p-2">اسم</th>
                    <th class="border border-gray-400 p-2">فعالية</th>
                    <th class="border border-gray-400 p-2">حذف</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </main>
    <script>
        // Fetch records from backend
        fetch('../backend/مدرسون.php')
            .then(response => response.json())
            .then(data => {
                const records = document.getElementById('records');
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="border border-gray-400 p-2">${record.اسم}</td>
                        <td class="border border-gray-400 p-2">
                            <a href="edit_مدرسون.php?id=${record.id}" class="text-emerald-600 hover:text-emerald-900">تعديل</a>
                        </td>
                        <td class="border border-gray-400 p-2">
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    records.appendChild(row);
                });
            })
            .catch(error => console.error(error));

        // Search functionality
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', searchRecords);

        function searchRecords() {
            const searchValue = searchInput.value;
            fetch('../backend/مدرسون.php?search=' + searchValue)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="border border-gray-400 p-2">${record.اسم}</td>
                            <td class="border border-gray-400 p-2">
                                <a href="edit_مدرسون.php?id=${record.id}" class="text-emerald-600 hover:text-emerald-900">تعديل</a>
                            </td>
                            <td class="border border-gray-400 p-2">
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                })
                .catch(error => console.error(error));
        }

        // Delete record functionality
        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/مدرسون.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

Note: This code assumes that you have a backend script (`مدرسون.php`) that handles the GET and DELETE requests. The backend script should return the list of records in JSON format, and handle the DELETE request by deleting the record with the specified ID.