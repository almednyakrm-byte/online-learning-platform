<?php

require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Get input data from JSON or POST
$inputData = json_decode(file_get_contents('php://input'), true);
if (empty($inputData)) {
    $inputData = $_POST;
}

// Function to validate and sanitize input data
function validateInput($data) {
    $validatedData = array();
    foreach ($data as $key => $value) {
        if (!empty($value)) {
            $validatedData[$key] = htmlspecialchars(strip_tags($value));
        }
    }
    return $validatedData;
}

// Function to handle CRUD operations
function handleCRUD($action, $data) {
    global $pdo, $userRole, $userID;

    // Check if user is logged in
    if ($userRole !== 'admin' && $userRole !== 'user') {
        http_response_code(401);
        echo json_encode(array('error' => 'Unauthorized'));
        return;
    }

    // Validate and sanitize input data
    $validatedData = validateInput($data);

    // Handle different CRUD operations
    switch ($action) {
        case 'GET':
            // Select all records
            $stmt = $pdo->prepare("SELECT * FROM مقررات");
            $stmt->execute();
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            echo json_encode($records);
            break;

        case 'POST':
            // Insert new record
            $stmt = $pdo->prepare("INSERT INTO مقررات (title, description, created_by) VALUES (:title, :description, :created_by)");
            $stmt->execute($validatedData);
            http_response_code(201);
            echo json_encode(array('message' => 'Record created successfully'));
            break;

        case 'PUT':
            // Update existing record
            if ($userRole !== 'admin') {
                http_response_code(403);
                echo json_encode(array('error' => 'Forbidden'));
                return;
            }
            $stmt = $pdo->prepare("UPDATE مقررات SET title = :title, description = :description WHERE id = :id AND created_by = :created_by");
            $stmt->execute($validatedData);
            http_response_code(200);
            echo json_encode(array('message' => 'Record updated successfully'));
            break;

        case 'DELETE':
            // Delete existing record
            if ($userRole !== 'admin') {
                http_response_code(403);
                echo json_encode(array('error' => 'Forbidden'));
                return;
            }
            $stmt = $pdo->prepare("DELETE FROM مقررات WHERE id = :id AND created_by = :created_by");
            $stmt->execute($validatedData);
            http_response_code(200);
            echo json_encode(array('message' => 'Record deleted successfully'));
            break;

        default:
            http_response_code(405);
            echo json_encode(array('error' => 'Method not allowed'));
            break;
    }
}

// Handle different HTTP requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    handleCRUD('GET', array());
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handleCRUD('POST', $inputData);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents('php://input'), $inputData);
    handleCRUD('PUT', $inputData);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents('php://input'), $inputData);
    handleCRUD('DELETE', $inputData);
} else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}



// Add headers to the response
header('Content-Type: application/json');