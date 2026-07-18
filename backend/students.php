<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Get request data
$data = json_decode(file_get_contents('php://input'), true);
if (empty($data)) {
    $data = $_POST;
}

// Connect to database
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET request
if ($method == 'GET') {
    // Validate and sanitize input
    $id = isset($data['id']) ? (int) $data['id'] : null;

    // SQL query structure
    if ($id) {
        $stmt = $pdo->prepare('SELECT * FROM students WHERE id = :id');
        $stmt->bindParam(':id', $id);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM students');
    }

    // Execute query
    $stmt->execute();

    // Output processing
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($students);
}

// Handle POST request
elseif ($method == 'POST') {
    // Validate and sanitize input
    $name = isset($data['name']) ? trim($data['name']) : null;
    $email = isset($data['email']) ? trim($data['email']) : null;

    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Check if input is valid
    if (empty($name) || empty($email)) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // SQL query structure
    $stmt = $pdo->prepare('INSERT INTO students (name, email) VALUES (:name, :email)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);

    // Execute query
    try {
        $stmt->execute();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Student created successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to create student']);
    }
}

// Handle PUT request
elseif ($method == 'PUT') {
    // Validate and sanitize input
    $id = isset($data['id']) ? (int) $data['id'] : null;
    $name = isset($data['name']) ? trim($data['name']) : null;
    $email = isset($data['email']) ? trim($data['email']) : null;

    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Check if input is valid
    if (empty($id) || empty($name) || empty($email)) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // SQL query structure
    $stmt = $pdo->prepare('UPDATE students SET name = :name, email = :email WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);

    // Execute query
    try {
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Student updated successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to update student']);
    }
}

// Handle DELETE request
elseif ($method == 'DELETE') {
    // Validate and sanitize input
    $id = isset($data['id']) ? (int) $data['id'] : null;

    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Check if input is valid
    if (empty($id)) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // SQL query structure
    $stmt = $pdo->prepare('DELETE FROM students WHERE id = :id');
    $stmt->bindParam(':id', $id);

    // Execute query
    try {
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Student deleted successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to delete student']);
    }
}

// Handle invalid request method
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}