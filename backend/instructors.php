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

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $instructor_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // SQL query structure: Select all instructors or a specific instructor by ID
    $sql = 'SELECT * FROM instructors';
    $params = [];

    if ($instructor_id) {
        $sql .= ' WHERE id = :id';
        $params[':id'] = $instructor_id;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Output processing: Fetch and return instructors data
    $instructors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($instructors);
}

// Handle POST requests (Create)
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $email = filter_var($data['email'] ?? null, FILTER_VALIDATE_EMAIL);

    if (!$name || !$email) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // SQL query structure: Insert new instructor
    $sql = 'INSERT INTO instructors (name, email) VALUES (:name, :email)';
    $params = [
        ':name' => $name,
        ':email' => $email,
    ];

    // Prepare and execute SQL query
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Instructor created successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to create instructor']);
    }
}

// Handle PUT requests (Update)
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $instructor_id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $email = filter_var($data['email'] ?? null, FILTER_VALIDATE_EMAIL);

    if (!$instructor_id || !$name || !$email) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // SQL query structure: Update existing instructor
    $sql = 'UPDATE instructors SET name = :name, email = :email WHERE id = :id';
    $params = [
        ':id' => $instructor_id,
        ':name' => $name,
        ':email' => $email,
    ];

    // Prepare and execute SQL query
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Instructor updated successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to update instructor']);
    }
}

// Handle DELETE requests
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $instructor_id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);

    if (!$instructor_id) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // SQL query structure: Delete existing instructor
    $sql = 'DELETE FROM instructors WHERE id = :id';
    $params = [
        ':id' => $instructor_id,
    ];

    // Prepare and execute SQL query
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Instructor deleted successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to delete instructor']);
    }
}

// Handle invalid request methods
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}