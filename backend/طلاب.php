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
try {
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
    exit;
}

// Function to check if user is logged in
function isLoggedIn() {
    // Replace with your actual session checking logic
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Function to check if user is admin
function isAdmin() {
    // Replace with your actual admin checking logic
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    
    // Check if user is logged in
    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    
    // SQL query structure: Select all or by ID
    if ($id) {
        $stmt = $pdo->prepare('SELECT * FROM طلاب WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
    } else {
        $stmt = $pdo->prepare('SELECT * FROM طلاب');
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
    // Check if user is logged in
    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    
    // Read input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input
    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'] ?? null, FILTER_SANITIZE_EMAIL);
    
    // SQL query structure: Insert
    $stmt = $pdo->prepare('INSERT INTO طلاب (name, email) VALUES (:name, :email)');
    $stmt->execute([':name' => $name, ':email' => $email]);
    
    // Output processing
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Created successfully']);
}

// Handle PUT requests
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is logged in and admin
    if (!isLoggedIn() || !isAdmin()) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    
    // Read input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input
    $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'] ?? null, FILTER_SANITIZE_EMAIL);
    
    // SQL query structure: Update
    $stmt = $pdo->prepare('UPDATE طلاب SET name = :name, email = :email WHERE id = :id');
    $stmt->execute([':id' => $id, ':name' => $name, ':email' => $email]);
    
    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Updated successfully']);
}

// Handle DELETE requests
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is logged in and admin
    if (!isLoggedIn() || !isAdmin()) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    
    // Read input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input
    $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    
    // SQL query structure: Delete
    $stmt = $pdo->prepare('DELETE FROM طلاب WHERE id = :id');
    $stmt->execute([':id' => $id]);
    
    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Deleted successfully']);
}