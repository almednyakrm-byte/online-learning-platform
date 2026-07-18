<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Get input from request body
$input = json_decode(file_get_contents('php://input'), true);

// Check if input is valid
if (!$input) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid input'));
    exit;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('SELECT * FROM مدرسون');
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return rows as JSON
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($rows);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database error'));
    }
}

// Handle POST request
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Validate input
        if (!isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input'));
            exit;
        }
        
        // Sanitize input
        $name = $pdo->quote($input['name']);
        $email = $pdo->quote($input['email']);
        $phone = $pdo->quote($input['phone']);
        
        // Prepare SQL query
        $stmt = $pdo->prepare('INSERT INTO مدرسون (name, email, phone) VALUES (:name, :email, :phone)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        
        // Return success message
        http_response_code(201);
        echo json_encode(array('message' => 'Teacher added successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database error'));
    }
}

// Handle PUT request
elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    try {
        // Validate input
        if (!isset($input['id']) || !isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input'));
            exit;
        }
        
        // Sanitize input
        $id = $pdo->quote($input['id']);
        $name = $pdo->quote($input['name']);
        $email = $pdo->quote($input['email']);
        $phone = $pdo->quote($input['phone']);
        
        // Prepare SQL query
        $stmt = $pdo->prepare('UPDATE مدرسون SET name = :name, email = :email, phone = :phone WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        
        // Return success message
        http_response_code(200);
        echo json_encode(array('message' => 'Teacher updated successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database error'));
    }
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    try {
        // Validate input
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input'));
            exit;
        }
        
        // Sanitize input
        $id = $pdo->quote($input['id']);
        
        // Prepare SQL query
        $stmt = $pdo->prepare('DELETE FROM مدرسون WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Return success message
        http_response_code(200);
        echo json_encode(array('message' => 'Teacher deleted successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database error'));
    }
}

// Return error message for invalid request method
else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}