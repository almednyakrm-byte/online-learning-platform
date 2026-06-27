<?php
// edit_طلاب.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_طلاب.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل طالب</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 bg-slate-900 p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl text-indigo-500 font-bold mb-4">تعديل طالب</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-indigo-500 text-sm font-bold mb-2">اسم الطالب</label>
                <input type="text" id="name" name="name" class="bg-slate-900 border border-indigo-500 p-2 rounded-lg w-full">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-indigo-500 text-sm font-bold mb-2">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="bg-slate-900 border border-indigo-500 p-2 rounded-lg w-full">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-slate-900 font-bold py-2 px-4 rounded-lg w-full">تعديل</button>
        </form>
    </div>

    <script>
        const id = <?php echo $id; ?>;
        const form = document.getElementById('edit-form');

        // Fetch existing record details
        fetch(`../backend/طلاب.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('name').value = data.name;
                document.getElementById('email').value = data.email;
            });

        // Submit form using AJAX
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('../backend/طلاب.php', {
                method: 'PUT',
                body: JSON.stringify({
                    id: id,
                    name: formData.get('name'),
                    email: formData.get('email')
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_طلاب.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>