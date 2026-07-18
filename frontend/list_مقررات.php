**list_مقررات.php**

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
    <title>مقررات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
        }
        .emerald-600 {
            color: #008E73;
        }
        .teal-500 {
            color: #0097A7;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-md p-4">
        <nav class="flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="document.location='logout.php'">تسجيل الخروج</button>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">مقررات</h1>
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="document.location='create_مقررات.php'">إضافة جديد</button>
        </div>
        <div class="flex justify-between items-center mb-4">
            <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-600" placeholder="بحث...">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">المسمى</th>
                    <th class="border border-gray-300 px-4 py-2">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2"><?= $record['name'] ?></td>
                        <td class="border border-gray-300 px-4 py-2">
                            <a href="edit_مقررات.php?id=<?= $record['id'] ?>" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?= $record['id'] ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </main>

    <script>
        function searchRecords() {
            const searchValue = document.getElementById('search').value;
            fetch('../backend/مقررات.php?search=' + searchValue)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="border border-gray-300 px-4 py-2">${record.name}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <a href="edit_مقررات.php?id=${record.id}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/مقررات.php', {
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
                .catch(error => console.error('Error:', error));
            }
        }

        function fetchRecords() {
            return fetch('../backend/مقررات.php')
                .then(response => response.json())
                .then(data => data.records);
        }
    </script>
</body>
</html>


**backend/مقررات.php**

<?php
// Fetch records from database
$records = array();
$records[] = array('id' => 1, 'name' => 'مقرر 1');
$records[] = array('id' => 2, 'name' => 'مقرر 2');
$records[] = array('id' => 3, 'name' => 'مقرر 3');

// Search records
if (isset($_GET['search'])) {
    $searchValue = $_GET['search'];
    $records = array_filter($records, function($record) use ($searchValue) {
        return strpos($record['name'], $searchValue) !== false;
    });
}

// Delete record
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = json_decode(file_get_contents('php://input'), true)['id'];
    // Delete record from database
    // ...
    echo json_encode(array('success' => true));
}

// Output records
echo json_encode(array('records' => $records));


Note: This is a basic implementation and you should adapt it to your specific needs and database schema. Additionally, you should implement proper error handling and security measures.