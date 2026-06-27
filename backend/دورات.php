<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get user role
$user_role = $_SESSION['user_role'];

// Get input data
$input_data = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is admin
    if ($user_role !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('SELECT * FROM دورات');
        $stmt->execute();
        $dorats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Output data
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($dorats);
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Internal Server Error']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is admin
    if ($user_role !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate input data
    if (!isset($input_data['name']) || !isset($input_data['description'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize input data
    $name = filter_var($input_data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($input_data['description'], FILTER_SANITIZE_STRING);

    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('INSERT INTO دورات (name, description) VALUES (:name, :description)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();

        // Output data
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Dorate created successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Internal Server Error']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($user_role !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate input data
    if (!isset($input_data['id']) || !isset($input_data['name']) || !isset($input_data['description'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize input data
    $id = filter_var($input_data['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($input_data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($input_data['description'], FILTER_SANITIZE_STRING);

    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('UPDATE دورات SET name = :name, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();

        // Output data
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Dorate updated successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Internal Server Error']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($user_role !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate input data
    if (!isset($input_data['id'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize input data
    $id = filter_var($input_data['id'], FILTER_SANITIZE_NUMBER_INT);

    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('DELETE FROM دورات WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Output data
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Dorate deleted successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Internal Server Error']);
    }
}