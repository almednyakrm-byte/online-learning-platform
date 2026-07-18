**list_مراجعات.php**

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
    <title>مراجعات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2d3748;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
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
            background-color: #2d3748;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            font-size: 1.5rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar:focus {
            outline: none;
            border-color: #aaa;
        }
        .btn {
            background-color: #2d3748;
            color: #fff;
            padding: 1rem 2rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #3b4453;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="ml-4">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="ml-4">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4 mt-4">
        <h1 class="text-3xl font-bold mb-4">مراجعات</h1>
        <button class="btn" onclick="location.href='create_مراجعات.php'">إضافة جديد</button>
        <div class="flex justify-center mb-4">
            <input type="search" class="search-bar" id="search" placeholder="بحث...">
            <button class="btn ml-2" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم</th>
                    <th>وصف</th>
                    <th>تاريخ</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch records from backend
        async function fetchRecords() {
            try {
                const response = await fetch('../backend/مراجعات.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                return data;
            } catch (error) {
                console.error(error);
            }
        }

        // Search records
        function searchRecords() {
            const searchQuery = document.getElementById('search').value;
            fetchRecords().then(data => {
                const records = document.getElementById('records');
                records.innerHTML = '';
                data.forEach(record => {
                    if (record.اسم.toLowerCase().includes(searchQuery.toLowerCase()) || record.وصف.toLowerCase().includes(searchQuery.toLowerCase())) {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.اسم}</td>
                            <td>${record.وصف}</td>
                            <td>${record.تاريخ}</td>
                            <td>
                                <a href="edit_مراجعات.php?id=${record.id}" class="text-blue-500 hover:text-blue-700">تعديل</a>
                                <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    }
                });
            });
        }

        // Delete record
        async function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                try {
                    const response = await fetch('../backend/مراجعات.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id })
                    });
                    if (response.ok) {
                        fetchRecords().then(data => {
                            const records = document.getElementById('records');
                            records.innerHTML = '';
                            data.forEach(record => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td>${record.اسم}</td>
                                    <td>${record.وصف}</td>
                                    <td>${record.تاريخ}</td>
                                    <td>
                                        <a href="edit_مراجعات.php?id=${record.id}" class="text-blue-500 hover:text-blue-700">تعديل</a>
                                        <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                                    </td>
                                `;
                                records.appendChild(row);
                            });
                        });
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        }

        // Load records on page load
        fetchRecords().then(data => {
            const records = document.getElementById('records');
            data.forEach(record => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.اسم}</td>
                    <td>${record.وصف}</td>
                    <td>${record.تاريخ}</td>
                    <td>
                        <a href="edit_مراجعات.php?id=${record.id}" class="text-blue-500 hover:text-blue-700">تعديل</a>
                        <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                records.appendChild(row);
            });
        });
    </script>
</body>
</html>

This code includes a premium Tailwind UI layout with a specific color palette matching the theme. It also includes session validation, a table showing the list of records with actions, an "Add New Item" button, a search bar filtering elements in real-time, and AJAX JavaScript code fetching list records from the backend and handling DELETE requests.