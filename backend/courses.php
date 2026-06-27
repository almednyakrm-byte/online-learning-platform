<?php
require_once 'db.php';

// Get user data from session
$user = $_SESSION['user'];

// Check if user is logged in
if (!isset($user)) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if user is admin
$is_admin = $user['role'] == 'admin';

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Validate course ID
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize course ID
    $id = intval($input['id']);

    // Query database for course
    $stmt = $pdo->prepare('SELECT * FROM courses WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch course data
    $course = $stmt->fetch();

    // Check if course exists
    if (!$course) {
        http_response_code(404);
        echo json_encode(array('error' => 'Course not found'));
        exit;
    }

    // Return course data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($course);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate course data
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize course data
    $name = trim($input['name']);
    $description = trim($input['description']);

    // Query database for new course
    $stmt = $pdo->prepare('INSERT INTO courses (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Get new course ID
    $new_id = $pdo->lastInsertId();

    // Return new course ID
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $new_id));
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Validate course ID and data
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize course ID and data
    $id = intval($input['id']);
    $name = trim($input['name']);
    $description = trim($input['description']);

    // Check if user is admin
    if (!$is_admin) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Query database for course
    $stmt = $pdo->prepare('SELECT * FROM courses WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch course data
    $course = $stmt->fetch();

    // Check if course exists
    if (!$course) {
        http_response_code(404);
        echo json_encode(array('error' => 'Course not found'));
        exit;
    }

    // Update course data
    $stmt = $pdo->prepare('UPDATE courses SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return updated course ID
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $id));
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Validate course ID
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize course ID
    $id = intval($input['id']);

    // Check if user is admin
    if (!$is_admin) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Query database for course
    $stmt = $pdo->prepare('SELECT * FROM courses WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch course data
    $course = $stmt->fetch();

    // Check if course exists
    if (!$course) {
        http_response_code(404);
        echo json_encode(array('error' => 'Course not found'));
        exit;
    }

    // Delete course
    $stmt = $pdo->prepare('DELETE FROM courses WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return deleted course ID
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $id));
}