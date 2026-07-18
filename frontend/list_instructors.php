<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}

// Get current user info
$current_user = $_SESSION['user'];

// Include database connection
include '../backend/db.php';

// Get instructors list from database
$instructors = array();
if ($result = $conn->query("SELECT * FROM instructors")) {
    while ($row = $result->fetch_assoc()) {
        $instructors[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructors List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-blue-500 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">Back to Index</a>
            <span class="text-lg font-bold">Welcome, <?php echo $current_user; ?></span>
            <a href="logout.php" class="text-lg font-bold text-orange-300">Logout</a>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">Instructors List</h1>
        <div class="flex justify-between mb-4">
            <a href="create_instructors.php" class="bg-orange-300 text-white px-4 py-2 rounded">Add New Item</a>
            <input type="search" id="search" class="px-4 py-2 rounded" placeholder="Search...">
        </div>
        <table id="instructors-table" class="w-full table-auto border border-collapse">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($instructors as $instructor) { ?>
                <tr>
                    <td class="px-4 py-2 border"><?php echo $instructor['id']; ?></td>
                    <td class="px-4 py-2 border"><?php echo $instructor['name']; ?></td>
                    <td class="px-4 py-2 border"><?php echo $instructor['email']; ?></td>
                    <td class="px-4 py-2 border">
                        <a href="edit_instructors.php?id=<?php echo $instructor['id']; ?>" class="text-blue-500">Edit</a>
                        <button class="text-red-500" onclick="deleteInstructor(<?php echo $instructor['id']; ?>)">Delete</button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>

    <script>
        // Fetch API to get instructors list
        async function getInstructors() {
            const response = await fetch('../backend/instructors.php');
            const data = await response.json();
            return data;
        }

        // Delete instructor using Fetch API
        async function deleteInstructor(id) {
            const response = await fetch('../backend/instructors.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            });
            const data = await response.json();
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting instructor');
            }
        }

        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const rows = document.querySelectorAll('#instructors-table tbody tr');
            rows.forEach(row => {
                const name = row.cells[1].textContent.toLowerCase();
                const email = row.cells[2].textContent.toLowerCase();
                if (name.includes(searchValue) || email.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>