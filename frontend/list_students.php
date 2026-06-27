**list_students.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include script
include 'script.php';

// Get students data
$students = json_decode(file_get_contents('../backend/students.php'), true);

// Search query
$searchQuery = $_GET['search'] ?? '';

?>

<div class="container mx-auto p-4 pt-6">
    <div class="flex justify-between mb-4">
        <h1 class="text-2xl font-bold">Students</h1>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" id="add-new-item">Add New Item</button>
    </div>

    <div class="flex justify-between mb-4">
        <input type="search" class="w-full p-2 mb-4 border border-gray-400 rounded" placeholder="Search students" id="search-input" value="<?= $searchQuery ?>">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" id="search-button">Search</button>
    </div>

    <table class="w-full border-collapse border border-gray-400">
        <thead>
            <tr>
                <th class="border border-gray-400 p-2">Name</th>
                <th class="border border-gray-400 p-2">Email</th>
                <th class="border border-gray-400 p-2">Actions</th>
            </tr>
        </thead>
        <tbody id="students-table">
            <?php foreach ($students as $student) : ?>
                <tr>
                    <td class="border border-gray-400 p-2"><?= $student['name'] ?></td>
                    <td class="border border-gray-400 p-2"><?= $student['email'] ?></td>
                    <td class="border border-gray-400 p-2">
                        <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" id="edit-button" data-id="<?= $student['id'] ?>">Edit</button>
                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" id="delete-button" data-id="<?= $student['id'] ?>">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal for adding new item -->
<div id="add-new-item-modal" class="hidden fixed inset-0 z-10 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white p-4 rounded shadow-md">
            <h2 class="text-lg font-bold mb-4">Add New Student</h2>
            <form id="add-new-item-form">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Name:</label>
                    <input type="text" class="w-full p-2 mb-4 border border-gray-400 rounded" id="name" name="name" pattern="[A-Za-z\u0600-\u06FF0-9\s]+">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email:</label>
                    <input type="email" class="w-full p-2 mb-4 border border-gray-400 rounded" id="email" name="email">
                </div>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Add</button>
            </form>
        </div>
    </div>
</div>

<!-- Script for adding new item -->
<script>
    const addNewItemForm = document.getElementById('add-new-item-form');
    const addNewItemModal = document.getElementById('add-new-item-modal');
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');

    addNewItemForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(addNewItemForm);
        const response = await fetch('../backend/students.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    });

    searchButton.addEventListener('click', () => {
        const searchQuery = searchInput.value.trim();
        if (searchQuery) {
            fetch('../backend/students.php', {
                method: 'GET',
                params: { search: searchQuery }
            })
            .then(response => response.json())
            .then(data => {
                const studentsTable = document.getElementById('students-table');
                studentsTable.innerHTML = '';
                data.forEach(student => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${student.name}</td>
                        <td>${student.email}</td>
                        <td>
                            <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" id="edit-button" data-id="${student.id}">Edit</button>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" id="delete-button" data-id="${student.id}">Delete</button>
                        </td>
                    `;
                    studentsTable.appendChild(row);
                });
            });
        } else {
            location.reload();
        }
    });

    // Edit button functionality
    document.addEventListener('click', async (e) => {
        if (e.target.id === 'edit-button') {
            const studentId = e.target.dataset.id;
            const response = await fetch('../backend/students.php', {
                method: 'GET',
                params: { id: studentId }
            });
            const data = await response.json();
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            nameInput.value = data.name;
            emailInput.value = data.email;
            addNewItemModal.classList.remove('hidden');
            addNewItemModal.classList.add('flex');
        }
    });

    // Delete button functionality
    document.addEventListener('click', async (e) => {
        if (e.target.id === 'delete-button') {
            const studentId = e.target.dataset.id;
            const response = await fetch('../backend/students.php', {
                method: 'DELETE',
                params: { id: studentId }
            });
            const data = await response.json();
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        }
    });

    // Close modal
    document.addEventListener('click', (e) => {
        if (e.target === addNewItemModal) {
            addNewItemModal.classList.remove('flex');
            addNewItemModal.classList.add('hidden');
        }
    });
</script>


**header.php**

<?php
// Include header navigation
include 'nav.php';
?>

<div class="flex justify-between mb-4">
    <a href="index.php" class="text-lg font-bold">Back to Index</a>
    <div class="flex items-center">
        <p class="text-lg font-bold mr-2"><?= $_SESSION['username'] ?></p>
        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" id="logout-button">Logout</button>
    </div>
</div>


**nav.php**

<nav class="bg-gray-800 text-white p-4">
    <ul class="flex justify-between">
        <li><a href="index.php" class="text-lg font-bold">Home</a></li>
        <li><a href="list_students.php" class="text-lg font-bold">Students</a></li>
        <li><a href="list_teachers.php" class="text-lg font-bold">Teachers</a></li>
    </ul>
</nav>


**script.php**

<?php
// Include script for adding new item
include 'script.php';
?>


**script.js**
javascript
// Add new item functionality
document.addEventListener('DOMContentLoaded', () => {
    const addNewItemButton = document.getElementById('add-new-item');
    addNewItemButton.addEventListener('click', () => {
        const addNewItemModal = document.getElementById('add-new-item-modal');
        addNewItemModal.classList.remove('hidden');
        addNewItemModal.classList.add('flex');
    });
});


**backend/students.php**

<?php
// Get students data
$students = array(
    array('id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'),
    array('id' => 2, 'name' => 'Jane Doe', 'email' => 'jane@example.com'),
    array('id' => 3, 'name' => 'Bob Smith', 'email' => 'bob@example.com')
);

// Search functionality
$searchQuery = $_GET['search'] ?? '';
if ($searchQuery) {
    $students = array_filter($students, function($student) use ($searchQuery) {
        return strpos($student['name'], $searchQuery) !== false || strpos($student['email'], $searchQuery) !== false;
    });
}

// Add new item functionality