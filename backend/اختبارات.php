<?php

// Import database connection settings
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data from JSON or POST
$input = json_decode(file_get_contents('php://input'), true);
if (empty($input)) {
    $input = $_POST;
}

// Define routes for CRUD operations
$routes = array(
    '/get' => 'getTests',
    '/create' => 'createTest',
    '/update' => 'updateTest',
    '/delete' => 'deleteTest'
);

// Route the request
$route = $_SERVER['REQUEST_URI'];
if (isset($routes[$route])) {
    $method = $routes[$route];
    $response = $method($input);
} else {
    http_response_code(404);
    echo json_encode(array('error' => 'Not Found'));
    exit;
}

// Output response
header('Content-Type: application/json');
echo json_encode($response);

// Define CRUD methods
function getTests($input) {
    global $pdo;
    
    // Validate input
    if (!isset($input['limit']) || !isset($input['offset'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    
    // Sanitize input
    $limit = (int) $input['limit'];
    $offset = (int) $input['offset'];
    
    // SQL query
    $stmt = $pdo->prepare('SELECT * FROM اختبارات LIMIT :limit OFFSET :offset');
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    // Fetch results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Output results
    return array('tests' => $results);
}

function createTest($input) {
    global $pdo;
    
    // Validate input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    
    // Sanitize input
    $name = trim($input['name']);
    $description = trim($input['description']);
    
    // SQL query
    $stmt = $pdo->prepare('INSERT INTO اختبارات (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->execute();
    
    // Get inserted ID
    $id = $pdo->lastInsertId();
    
    // Output result
    return array('test' => array('id' => $id, 'name' => $name, 'description' => $description));
}

function updateTest($input) {
    global $pdo;
    
    // Validate input
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    
    // Sanitize input
    $id = (int) $input['id'];
    $name = trim($input['name']);
    $description = trim($input['description']);
    
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    // SQL query
    $stmt = $pdo->prepare('UPDATE اختبارات SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->execute();
    
    // Output result
    return array('test' => array('id' => $id, 'name' => $name, 'description' => $description));
}

function deleteTest($input) {
    global $pdo;
    
    // Validate input
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    
    // Sanitize input
    $id = (int) $input['id'];
    
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    // SQL query
    $stmt = $pdo->prepare('DELETE FROM اختبارات WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    // Output result
    return array('message' => 'Test deleted successfully');
}

?>