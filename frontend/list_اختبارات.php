**list_اختبارات.php**

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
    <title>اختبارات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #1a1d23;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar button[type="submit"] {
            background-color: #1a1d23;
            color: #fff;
            border: none;
            padding: 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .search-bar button[type="submit"]:hover {
            background-color: #1a1d23;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الصفحة الرئيسية</a>
        <span class="text-indigo-500">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">اختبارات</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_اختبارات.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button type="submit" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>رقم</th>
                    <th>اسم</th>
                    <th>تاريخ</th>
                    <th>حالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?php echo $record['id']; ?></td>
                        <td><?php echo $record['name']; ?></td>
                        <td><?php echo $record['date']; ?></td>
                        <td><?php echo $record['status']; ?></td>
                        <td>
                            <a href="edit_اختبارات.php?id=<?php echo $record['id']; ?>" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?php echo $record['id']; ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const searchInput = document.getElementById('search-input');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetchRecords(searchQuery);
            } else {
                fetchRecords();
            }
        }

        function fetchRecords(searchQuery = '') {
            const url = '../backend/اختبارات.php';
            const params = new URLSearchParams({
                search: searchQuery,
            });
            fetch(`${url}?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    const recordsTable = document.getElementById('records-table');
                    recordsTable.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.id}</td>
                            <td>${record.name}</td>
                            <td>${record.date}</td>
                            <td>${record.status}</td>
                            <td>
                                <a href="edit_اختبارات.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        recordsTable.appendChild(row);
                    });
                })
                .catch(error => console.error(error));
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch(`../backend/اختبارات.php?delete=${id}`, {
                    method: 'DELETE',
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            fetchRecords();
                        } else {
                            console.error(data.error);
                        }
                    })
                    .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

<?php
function fetchRecords() {
    $url = '../backend/اختبارات.php';
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    return $data;
}
?>


**backend/اختبارات.php**

<?php
// Fetch records from database
$records = array();
$records[] = array(
    'id' => 1,
    'name' => 'سجل 1',
    'date' => '2022-01-01',
    'status' => 'مفعل',
);
$records[] = array(
    'id' => 2,
    'name' => 'سجل 2',
    'date' => '2022-01-15',
    'status' => 'مفعل',
);
$records[] = array(
    'id' => 3,
    'name' => 'سجل 3',
    'date' => '2022-02-01',
    'status' => 'مفعل',
);

// Search query
$searchQuery = $_GET['search'] ?? '';

// Filter records
$filteredRecords = array();
if ($searchQuery) {
    foreach ($records as $record) {
        if (strpos($record['name'], $searchQuery) !== false) {
            $filteredRecords[] = $record;
        }
    }
} else {
    $filteredRecords = $records;
}

// Output records as JSON
header('Content-Type: application/json');
echo json_encode($filteredRecords);
?>


Note: This code assumes that you have a database setup to store the records. You should replace the hardcoded records in the `backend/اختبارات.php` file with a database query to fetch the records.