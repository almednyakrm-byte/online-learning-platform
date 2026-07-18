<?php

require_once 'db.php';

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if the user is an admin
if ($method === 'PUT' || $method === 'DELETE') {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
}

// Get the input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($method === 'GET') {
    try {
        // Prepare the SQL query
        $stmt = $pdo->prepare('SELECT * FROM أساتذة');
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Output the data
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle POST request
if ($method === 'POST') {
    try {
        // Validate the input data
        if (!isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Bad Request'));
            exit;
        }
        
        // Sanitize the input data
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
        $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);
        
        // Prepare the SQL query
        $stmt = $pdo->prepare('INSERT INTO أساتذة (name, email, phone) VALUES (:name, :email, :phone)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        
        // Output the result
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Teacher created successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle PUT request
if ($method === 'PUT') {
    try {
        // Validate the input data
        if (!isset($input['id']) || !isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Bad Request'));
            exit;
        }
        
        // Sanitize the input data
        $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
        $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);
        
        // Prepare the SQL query
        $stmt = $pdo->prepare('UPDATE أساتذة SET name = :name, email = :email, phone = :phone WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        
        // Output the result
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Teacher updated successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle DELETE request
if ($method === 'DELETE') {
    try {
        // Validate the input data
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Bad Request'));
            exit;
        }
        
        // Sanitize the input data
        $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
        
        // Prepare the SQL query
        $stmt = $pdo->prepare('DELETE FROM أساتذة WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Output the result
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Teacher deleted successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}