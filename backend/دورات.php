<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get user role
$user_role = $_SESSION['role'];

// Check if user is admin
$is_admin = ($user_role == 'admin');

// Get input data
$input_data = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Validate input
    if (!isset($input_data['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input
    $id = filter_var($input_data['id'], FILTER_SANITIZE_NUMBER_INT);

    // Query database
    $stmt = $pdo->prepare('SELECT * FROM دورات WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch result
    $result = $stmt->fetch();

    // Check if result exists
    if ($result) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input
    if (!isset($input_data['name']) || !isset($input_data['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input
    $name = filter_var($input_data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($input_data['description'], FILTER_SANITIZE_STRING);

    // Query database
    $stmt = $pdo->prepare('INSERT INTO دورات (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Get inserted ID
    $id = $pdo->lastInsertId();

    // Check if user is admin
    if ($is_admin) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('id' => $id, 'name' => $name, 'description' => $description));
    } else {
        http_response_code(201);
        echo json_encode(array('id' => $id));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Validate input
    if (!isset($input_data['id']) || !isset($input_data['name']) || !isset($input_data['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input
    $id = filter_var($input_data['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($input_data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($input_data['description'], FILTER_SANITIZE_STRING);

    // Check if user is admin
    if (!$is_admin) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Query database
    $stmt = $pdo->prepare('UPDATE دورات SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Check if result exists
    $stmt = $pdo->prepare('SELECT * FROM دورات WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch();

    // Check if result exists
    if ($result) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Validate input
    if (!isset($input_data['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input
    $id = filter_var($input_data['id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if user is admin
    if (!$is_admin) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Query database
    $stmt = $pdo->prepare('DELETE FROM دورات WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Check if result exists
    $stmt = $pdo->prepare('SELECT * FROM دورات WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch();

    // Check if result exists
    if (!$result) {
        http_response_code(204);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
}