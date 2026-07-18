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

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Check if user is admin for edit and delete operations
function isAdmin() {
    return $_SESSION['user_role'] === 'admin';
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // SQL query structure
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
    exit;
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $data = json_decode(file_get_contents('php://input'), true);
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $email = filter_var($data['email'] ?? null, FILTER_SANITIZE_EMAIL);

    // Check if user is admin
    if (!isAdmin()) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query structure
    $stmt = $pdo->prepare('INSERT INTO معلمين (name, email) VALUES (:name, :email)');
    $stmt->execute([':name' => $name, ':email' => $email]);

    // Output processing
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Created successfully']);
    exit;
}

// Handle PUT requests
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate and sanitize input
    $data = json_decode(file_get_contents('php://input'), true);
    $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $email = filter_var($data['email'] ?? null, FILTER_SANITIZE_EMAIL);

    // Check if user is admin
    if (!isAdmin()) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query structure
    $stmt = $pdo->prepare('UPDATE معلمين SET name = :name, email = :email WHERE id = :id');
    $stmt->execute([':id' => $id, ':name' => $name, ':email' => $email]);

    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Updated successfully']);
    exit;
}

// Handle DELETE requests
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate and sanitize input
    $data = json_decode(file_get_contents('php://input'), true);
    $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);

    // Check if user is admin
    if (!isAdmin()) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query structure
    $stmt = $pdo->prepare('DELETE FROM معلمين WHERE id = :id');
    $stmt->execute([':id' => $id]);

    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Deleted successfully']);
    exit;
}

// Handle other requests
http_response_code(405);
header('Content-Type: application/json');
echo json_encode(['error' => 'Method not allowed']);
exit;