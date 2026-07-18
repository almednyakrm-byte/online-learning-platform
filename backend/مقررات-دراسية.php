<?php
// Import database connection
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

// Initialize database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET requests
if ($method == 'GET') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // Check if id is provided
    if ($id) {
        // Select single record
        $stmt = $pdo->prepare('SELECT * FROM مقررات_دراسية WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $record = $stmt->fetch();

        // Check if record exists
        if ($record) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($record);
        } else {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Record not found']);
        }
    } else {
        // Select all records
        $stmt = $pdo->prepare('SELECT * FROM مقررات_دراسية');
        $stmt->execute();
        $records = $stmt->fetchAll();

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($records);
    }
}

// Handle POST requests
if ($method == 'POST') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'] ?? null, FILTER_SANITIZE_STRING);

    // Check if required fields are provided
    if (!$name || !$description) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Insert new record
    $stmt = $pdo->prepare('INSERT INTO مقررات_دراسية (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record created successfully']);
}

// Handle PUT requests
if ($method == 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'] ?? null, FILTER_SANITIZE_STRING);

    // Check if required fields are provided
    if (!$id || !$name || !$description) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Update existing record
    $stmt = $pdo->prepare('UPDATE مقررات_دراسية SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record updated successfully']);
}

// Handle DELETE requests
if ($method == 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);

    // Check if id is provided
    if (!$id) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Delete record
    $stmt = $pdo->prepare('DELETE FROM مقررات_دراسية WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record deleted successfully']);
}