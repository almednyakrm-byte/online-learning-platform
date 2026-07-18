<?php

require_once 'db.php';

// Get inputs from JSON body or POST data
$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Validate input data
if (!isset($input['id']) && !isset($input['name']) && !isset($input['description'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request data']);
    exit;
}

// Sanitize input data
$input['name'] = trim($input['name'] ?? '');
$input['description'] = trim($input['description'] ?? '');

// Handle GET request
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM نتائج WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $result = $stmt->fetch();
    if (!$result) {
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
        exit;
    }
    http_response_code(200);
    echo json_encode($result);
    exit;
}

// Handle GET request for all records
if (isset($_GET['limit']) && isset($_GET['offset'])) {
    $stmt = $pdo->prepare('SELECT * FROM نتائج ORDER BY id LIMIT :limit OFFSET :offset');
    $stmt->bindParam(':limit', $_GET['limit']);
    $stmt->bindParam(':offset', $_GET['offset']);
    $stmt->execute();
    $results = $stmt->fetchAll();
    http_response_code(200);
    echo json_encode($results);
    exit;
}

// Handle POST request
if (isset($input['name']) && isset($input['description'])) {
    $stmt = $pdo->prepare('INSERT INTO نتائج (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(['message' => 'Created successfully']);
        exit;
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
        exit;
    }
}

// Handle PUT request
if (isset($input['id']) && isset($input['name']) && isset($input['description'])) {
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    $stmt = $pdo->prepare('UPDATE نتائج SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['message' => 'Updated successfully']);
        exit;
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
        exit;
    }
}

// Handle DELETE request
if (isset($input['id'])) {
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    $stmt = $pdo->prepare('DELETE FROM نتائج WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    if ($stmt->execute()) {
        http_response_code(204);
        echo json_encode(['message' => 'Deleted successfully']);
        exit;
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
        exit;
    }
}

http_response_code(400);
echo json_encode(['error' => 'Invalid request']);
exit;



// Add the following code to your db.php file to handle PDO connection
$pdo = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);