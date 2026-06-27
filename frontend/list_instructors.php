**list_instructors.php**

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
    <title>Instructors</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Custom styles for Arabic characters */
        .arabic-input {
            direction: rtl;
            text-align: right;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-md p-4">
        <nav class="flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold">Home</a>
            <div class="flex items-center">
                <p class="mr-2">Welcome, <?php echo $_SESSION['username']; ?></p>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">Logout</button>
            </div>
        </nav>
    </header>
    <main class="max-w-7xl mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Instructors</h1>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="openModal()">Add New Instructor</button>
        <div class="mt-4">
            <input type="search" class="w-full p-2 mb-2" id="search" placeholder="Search...">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="instructors-list">
                    <!-- List of instructors will be rendered here -->
                </tbody>
            </table>
        </div>
    </main>

    <!-- Modal for adding new instructor -->
    <div id="add-instructor-modal" class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 w-full max-w-md">
                <div class="bg-white rounded-t-lg px-4 pt-2">
                    <h2 class="text-lg font-bold">Add New Instructor</h2>
                </div>
                <div class="p-4">
                    <form id="add-instructor-form">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" class="block w-full px-3 py-2 mt-1 text-gray-700 border rounded-md dark:bg-gray-700 dark:text-gray-300" id="name" placeholder="Name" required>
                        </div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Instructor</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fetch@2.0.3/dist/fetch.min.js"></script>
    <script>
        // Open modal for adding new instructor
        function openModal() {
            document.getElementById('add-instructor-modal').style.display = 'block';
        }

        // Close modal for adding new instructor
        function closeModal() {
            document.getElementById('add-instructor-modal').style.display = 'none';
        }

        // Add event listener for form submission
        document.getElementById('add-instructor-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const name = document.getElementById('name').value;
            if (name.trim() === '') {
                alert('Please enter a name');
                return;
            }
            const response = await fetch('../backend/instructors.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ name })
            });
            const data = await response.json();
            if (data.success) {
                closeModal();
                document.getElementById('search').value = '';
                fetchInstructors();
            } else {
                alert(data.message);
            }
        });

        // Fetch list of instructors
        async function fetchInstructors() {
            const search = document.getElementById('search').value.trim();
            const response = await fetch('../backend/instructors.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                },
                params: {
                    search
                }
            });
            const data = await response.json();
            const instructorsList = document.getElementById('instructors-list');
            instructorsList.innerHTML = '';
            data.instructors.forEach((instructor) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-4 py-2">${instructor.name}</td>
                    <td class="px-4 py-2">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="openEditModal(${instructor.id})">Edit</button>
                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteInstructor(${instructor.id})">Delete</button>
                    </td>
                `;
                instructorsList.appendChild(row);
            });
        }

        // Open modal for editing instructor
        function openEditModal(id) {
            fetchInstructor(id).then((data) => {
                document.getElementById('edit-instructor-name').value = data.name;
                document.getElementById('edit-instructor-modal').style.display = 'block';
            });
        }

        // Fetch instructor data
        async function fetchInstructor(id) {
            const response = await fetch('../backend/instructors.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                },
                params: {
                    id
                }
            });
            return await response.json();
        }

        // Edit instructor
        async function editInstructor(id) {
            const name = document.getElementById('edit-instructor-name').value;
            if (name.trim() === '') {
                alert('Please enter a name');
                return;
            }
            const response = await fetch('../backend/instructors.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id, name })
            });
            const data = await response.json();
            if (data.success) {
                document.getElementById('edit-instructor-modal').style.display = 'none';
                fetchInstructors();
            } else {
                alert(data.message);
            }
        }

        // Delete instructor
        async function deleteInstructor(id) {
            if (confirm('Are you sure you want to delete this instructor?')) {
                const response = await fetch('../backend/instructors.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                });
                const data = await response.json();
                if (data.success) {
                    fetchInstructors();
                } else {
                    alert(data.message);
                }
            }
        }

        // Close modal for editing instructor
        function closeEditModal() {
            document.getElementById('edit-instructor-modal').style.display = 'none';
        }

        // Add event listener for form submission
        document.getElementById('edit-instructor-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            await editInstructor(document.getElementById('edit-instructor-id').value);
            closeEditModal();
        });

        // Fetch list of instructors on page load
        fetchInstructors();
    </script>
</body>
</html>


**instructors.php** (backend)

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to add instructor
function addInstructor($name) {
    global $conn;
    $sql = "INSERT INTO instructors (name) VALUES ('$name')";
    if ($conn->query($sql) === TRUE) {
        return array('success' => true, 'message' => 'Instructor added successfully');
    } else {
        return array('success' => false, 'message' => 'Error adding instructor');
    }
}

// Function to edit instructor
function editInstructor($id, $name) {
    global $conn;
    $sql = "UPDATE instructors SET name = '$name' WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
        return array('success' => true, 'message' => 'Instructor updated successfully');
    } else {
        return array('success' => false, 'message' => 'Error updating instructor');
    }
}

// Function to delete instructor
function deleteInstructor($id) {
    global $conn;
    $sql = "DELETE FROM instructors WHERE id = '$id'";
    if ($conn->query