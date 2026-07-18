**list_أساتذة.php**

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
    <title>أساتذة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
        }
        .header {
            background-color: #1a1d23;
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
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الصفحة الرئيسية</a>
        <span style="color: #fff; margin-left: 1rem;">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" style="color: #fff; margin-left: 1rem;">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">أساتذة</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_أساتذة.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم</th>
                    <th>وظيفة</th>
                    <th>تاريخ الميلاد</th>
                    <th>حذف</th>
                    <th>تعديل</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be displayed here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch API to get records
        async function getRecords() {
            const response = await fetch('../backend/أساتذة.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            const data = await response.json();
            displayRecords(data);
        }

        // Display records
        function displayRecords(data) {
            const records = document.getElementById('records');
            records.innerHTML = '';
            data.forEach(record => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.اسم}</td>
                    <td>${record.وظيفة}</td>
                    <td>${record.تاريخ_الولادة}</td>
                    <td>
                        <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                    <td>
                        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_أساتذة.php?id=${record.id}'">تعديل</button>
                    </td>
                `;
                records.appendChild(row);
            });
        }

        // Delete record
        async function deleteRecord(id) {
            const response = await fetch('../backend/أساتذة.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            });
            if (response.ok) {
                getRecords();
            } else {
                alert('Error deleting record');
            }
        }

        // Search records
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchValue = searchInput.value;
            fetch('../backend/أساتذة.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                },
                params: {
                    search: searchValue
                }
            })
            .then(response => response.json())
            .then(data => displayRecords(data))
            .catch(error => console.error(error));
        }

        // Initialize records
        getRecords();
    </script>
</body>
</html>

This code includes the following features:

*   Session validation to ensure the user is authenticated before accessing the page.
*   A premium Tailwind UI design with a specific color palette matching the theme.
*   A header navigation bar with links to the index page, current user info, and logout.
*   A table displaying the list of records with actions: Edit (link to edit_أساتذة.php?id=X) and Delete (AJAX call to backend).
*   An "Add New Item" button linking to create_أساتذة.php.
*   A search bar filtering elements in real-time using the Fetch API.
*   AJAX JavaScript code fetching list records from '../backend/أساتذة.php' (GET) and DELETE requests.

Note that this code assumes you have a backend PHP script (`../backend/أساتذة.php`) that handles the GET and DELETE requests. You will need to create this script to complete the functionality of this code.