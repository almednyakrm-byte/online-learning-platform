<?php
// Import database connection
require_once 'db.php';

// Initialize database connection
$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);

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
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    
    // SQL query structure: Select all or by ID
    if ($id) {
        $stmt = $pdo->prepare('SELECT * FROM معلمين WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
    } else {
        $stmt = $pdo->prepare('SELECT * FROM معلمين');
        $stmt->execute();
        $result = $stmt->fetchAll();
    }
    
    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($result);
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    
    // Read inputs
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input
    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'] ?? null, FILTER_SANITIZE_EMAIL);
    
    // SQL query structure: Insert
    $stmt = $pdo->prepare('INSERT INTO معلمين (name, email) VALUES (:name, :email)');
    $stmt->execute([':name' => $name, ':email' => $email]);
    
    // Output processing
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Created successfully']);
}

// Handle PUT requests
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    
    // Read inputs
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input
    $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'] ?? null, FILTER_SANITIZE_EMAIL);
    
    // SQL query structure: Update
    $stmt = $pdo->prepare('UPDATE معلمين SET name = :name, email = :email WHERE id = :id');
    $stmt->execute([':id' => $id, ':name' => $name, ':email' => $email]);
    
    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Updated successfully']);
}

// Handle DELETE requests
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    
    // Read inputs
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input
    $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    
    // SQL query structure: Delete
    $stmt = $pdo->prepare('DELETE FROM معلمين WHERE id = :id');
    $stmt->execute([':id' => $id]);
    
    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Deleted successfully']);
}