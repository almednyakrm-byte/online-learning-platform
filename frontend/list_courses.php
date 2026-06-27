**list_courses.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/fetch@2.0.4/dist/fetch.min.js"></script>
</head>
<body>
    <header class="bg-gray-800 text-white p-4">
        <nav class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold">Home</a>
            <div class="flex items-center">
                <p class="mr-4">Welcome, <?= $_SESSION['username'] ?></p>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">Logout</button>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Courses</h1>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="openModal()">Add New Item</button>
        <div class="flex justify-between items-center mb-4">
            <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="Search...">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">Search</button>
        </div>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border border-gray-400 p-2">ID</th>
                    <th class="border border-gray-400 p-2">Name</th>
                    <th class="border border-gray-400 p-2">Actions</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $response = fetchRecords();
                $records = json_decode($response, true);
                foreach ($records as $record) {
                    echo '<tr>';
                    echo '<td class="border border-gray-400 p-2">' . $record['id'] . '</td>';
                    echo '<td class="border border-gray-400 p-2">' . $record['name'] . '</td>';
                    echo '<td class="border border-gray-400 p-2">';
                    echo '<button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" onclick="editRecord(' . $record['id'] . ')">Edit</button>';
                    echo '<button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(' . $record['id'] . ')">Delete</button>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </main>

    <!-- Modal -->
    <div id="modal" class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Add New Item</h3>
                            <div class="mt-2">
                                <form id="form">
                                    <div class="mb-4">
                                        <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
                                        <input type="text" id="name" name="name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Enter name" pattern="[A-Za-z\u0600-\u06FF0-9\s]+">
                                    </div>
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto" onclick="closeModal()">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Open modal
        function openModal() {
            document.getElementById('modal').style.display = 'block';
        }

        // Close modal
        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        // Search records
        function searchRecords() {
            const search = document.getElementById('search').value;
            fetchRecords(search);
        }

        // Fetch records from backend
        function fetchRecords(search = '') {
            const url = '../backend/courses.php';
            const params = new URLSearchParams({
                search: search
            });
            const response = fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                },
                params: params
            });
            return response.text();
        }

        // Edit record
        function editRecord(id) {
            const url = '../backend/courses.php';
            const params = new URLSearchParams({
                id: id,
                action: 'edit'
            });
            const response = fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                },
                params: params
            });
            response.text().then(data => {
                const record = JSON.parse(data);
                document.getElementById('name').value = record.name;
                document.getElementById('form').action = '../backend/courses.php?action=update&id=' + id;
                openModal();
            });
        }

        // Delete record
        function deleteRecord(id) {
            if (confirm('Are you sure you want to delete this record?')) {
                const url = '../backend/courses.php';
                const params = new URLSearchParams({
                    id: id,
                    action: 'delete'
                });
                fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    params: params
                });
                const records = document.getElementById('records');
                records.innerHTML = '';
                fetchRecords();
            }
        }

        // Add new item
        document.getElementById('form').addEventListener('submit', event => {
            event.preventDefault();
            const name = document.getElementById('name').value;
            const url = '../backend/courses.php';
            const params = new URLSearchParams({
                name: name,
                action: 'add'
            });
            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                },
                params: params
            });
            closeModal();
            fetchRecords();
        });
    </script>
</body>
</html>


**courses.php (backend)**

<?php
// Database