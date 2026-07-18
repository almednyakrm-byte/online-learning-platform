<?php
require_once 'db.php';

// Get the input data
$input = json_decode(file_get_contents('php://input'), true);

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Check if the user is an admin
if (isset($input['action']) && in_array($input['action'], ['PUT', 'DELETE'])) {
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
}

// Handle GET request
if (isset($input['action']) && $input['action'] === 'GET') {
    // Validate the input
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize the input
    $id = (int) $input['id'];

    // Prepare the SQL query
    $stmt = $pdo->prepare('SELECT * FROM مراجعات WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch();

    // Return the result
    if ($result) {
        http_response_code(200);
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
    }
} elseif (isset($input['action']) && $input['action'] === 'GET_ALL') {
    // Prepare the SQL query
    $stmt = $pdo->prepare('SELECT * FROM مراجعات');
    $stmt->execute();

    // Fetch the results
    $results = $stmt->fetchAll();

    // Return the results
    http_response_code(200);
    echo json_encode($results);
} elseif (isset($input['action']) && $input['action'] === 'POST') {
    // Validate the input
    if (!isset($input['title']) || !isset($input['content'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize the input
    $title = trim($input['title']);
    $content = trim($input['content']);

    // Prepare the SQL query
    $stmt = $pdo->prepare('INSERT INTO مراجعات (title, content) VALUES (:title, :content)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->execute();

    // Return the result
    http_response_code(201);
    echo json_encode(['message' => 'Review created successfully']);
} elseif (isset($input['action']) && $input['action'] === 'PUT') {
    // Validate the input
    if (!isset($input['id']) || !isset($input['title']) || !isset($input['content'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize the input
    $id = (int) $input['id'];
    $title = trim($input['title']);
    $content = trim($input['content']);

    // Prepare the SQL query
    $stmt = $pdo->prepare('UPDATE مراجعات SET title = :title, content = :content WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->execute();

    // Return the result
    http_response_code(200);
    echo json_encode(['message' => 'Review updated successfully']);
} elseif (isset($input['action']) && $input['action'] === 'DELETE') {
    // Validate the input
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize the input
    $id = (int) $input['id'];

    // Prepare the SQL query
    $stmt = $pdo->prepare('DELETE FROM مراجعات WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return the result
    http_response_code(200);
    echo json_encode(['message' => 'Review deleted successfully']);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}



// Set the response headers
header('Content-Type: application/json');