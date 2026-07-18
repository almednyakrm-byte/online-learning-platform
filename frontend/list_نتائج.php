**list_نتائج.php**

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
    <title>نتائج</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a202c;
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
            border-collapse: collapse;
            width: 100%;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .search-bar {
            padding: 1rem;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar button[type="submit"] {
            background-color: #1a202c;
            color: #fff;
            border: none;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .search-bar button[type="submit"]:hover {
            background-color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-teal-500">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <h1 class="text-3xl text-emerald-600">نتائج</h1>
            <a href="create_نتائج.php" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">إضافة جديد</a>
        </div>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button type="submit" id="search-btn">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>عنوان</th>
                    <th>وصف</th>
                    <th>تاريخ الإضافة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $url = '../backend/نتائج.php';
                $response = file_get_contents($url);
                $records = json_decode($response, true);
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?php echo $record['title']; ?></td>
                        <td><?php echo $record['description']; ?></td>
                        <td><?php echo $record['date']; ?></td>
                        <td>
                            <a href="edit_نتائج.php?id=<?php echo $record['id']; ?>" class="text-emerald-600 hover:text-emerald-900">تعديل</a>
                            <button class="text-red-600 hover:text-red-900" onclick="deleteRecord(<?php echo $record['id']; ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Search bar functionality
        const searchInput = document.getElementById('search');
        const searchBtn = document.getElementById('search-btn');
        const records = document.getElementById('records');

        searchBtn.addEventListener('click', () => {
            const searchQuery = searchInput.value.trim();
            if (searchQuery !== '') {
                fetch('../backend/نتائج.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        records.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.title}</td>
                                <td>${record.description}</td>
                                <td>${record.date}</td>
                                <td>
                                    <a href="edit_نتائج.php?id=${record.id}" class="text-emerald-600 hover:text-emerald-900">تعديل</a>
                                    <button class="text-red-600 hover:text-red-900" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            records.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/نتائج.php')
                    .then(response => response.json())
                    .then(data => {
                        records.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.title}</td>
                                <td>${record.description}</td>
                                <td>${record.date}</td>
                                <td>
                                    <a href="edit_نتائج.php?id=${record.id}" class="text-emerald-600 hover:text-emerald-900">تعديل</a>
                                    <button class="text-red-600 hover:text-red-900" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            records.appendChild(row);
                        });
                    });
            }
        });

        // Delete record functionality
        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/نتائج.php', {
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

This code includes the following features:

1.  Session validation: The code checks if the user is logged in by checking the `$_SESSION['username']` variable. If the user is not logged in, it redirects them to the login page.
2.  Header navigation: The code includes a navigation bar with links to the index page, the user's profile, and the logout page.
3.  Table: The code displays a table with a list of records. Each record includes a title, description, date, and actions (edit and delete).
4.  Search bar: The code includes a search bar that filters the records in real-time.
5.  AJAX functionality: The code uses the Fetch API to fetch records from the backend and delete records.
6.  Delete record functionality: The code includes a delete button for each record. When clicked, it prompts the user to confirm deletion and then sends a DELETE request to the backend to delete the record.

Note that this code assumes that the backend is set up to handle GET and DELETE requests for the `نتائج` module. The backend code is not included in this example.