<?php
// Import database connection file
require_once 'db.php';

// Initialize database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if user is logged in
function isLoggedIn() {
    // Replace with actual session or token-based authentication logic
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function isAdmin() {
    // Replace with actual session or token-based authentication logic
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input parameters
    $courseId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // Check if user is logged in
    if (!isLoggedIn()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Retrieve all courses or a specific course
    if ($courseId === null) {
        $stmt = $pdo->prepare('SELECT * FROM courses');
        $stmt->execute();
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($courses);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM courses WHERE id = :id');
        $stmt->bindParam(':id', $courseId);
        $stmt->execute();
        $course = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($course === false) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Course not found']);
        } else {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($course);
        }
    }
}

// Handle POST requests
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is logged in and admin
    if (!isLoggedIn() || !isAdmin()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'] ?? null, FILTER_SANITIZE_STRING);

    // Check for required fields
    if ($name === null || $description === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // Insert new course
    $stmt = $pdo->prepare('INSERT INTO courses (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    $courseId = $pdo->lastInsertId();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $courseId, 'name' => $name, 'description' => $description]);
}

// Handle PUT requests
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is logged in and admin
    if (!isLoggedIn() || !isAdmin()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    $courseId = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'] ?? null, FILTER_SANITIZE_STRING);

    // Check for required fields
    if ($courseId === null || $name === null || $description === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // Update existing course
    $stmt = $pdo->prepare('UPDATE courses SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $courseId);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Course not found']);
    } else {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['id' => $courseId, 'name' => $name, 'description' => $description]);
    }
}

// Handle DELETE requests
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is logged in and admin
    if (!isLoggedIn() || !isAdmin()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    $courseId = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);

    // Check for required fields
    if ($courseId === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // Delete existing course
    $stmt = $pdo->prepare('DELETE FROM courses WHERE id = :id');
    $stmt->bindParam(':id', $courseId);
    $stmt->execute();
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Course not found']);
    } else {
        http_response_code(204);
        header('Content-Type: application/json');
    }
}

// Handle invalid request methods
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}