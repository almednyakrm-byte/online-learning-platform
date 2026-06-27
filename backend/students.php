<?php
require_once 'db.php';

// Get the input data from the request body
$input = json_decode(file_get_contents('php://input'), true);

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if the user is an admin
if (isset($input['id']) || isset($input['delete'])) {
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
}

// Handle GET request
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM students WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $student = $stmt->fetch();
    if ($student) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($student);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not Found'));
    }
} elseif (isset($_GET['all'])) {
    $stmt = $pdo->prepare('SELECT * FROM students');
    $stmt->execute();
    $students = $stmt->fetchAll();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($students);
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Bad Request'));
}

// Handle POST request
if (isset($input['name']) && isset($input['email']) && isset($input['phone'])) {
    // Validate and sanitize the input data
    if (!preg_match('/^[a-zA-Z ]+$/', $input['name'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid name'));
        exit;
    }
    if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid email'));
        exit;
    }
    if (!preg_match('/^\d{10}$/', $input['phone'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid phone number'));
        exit;
    }

    // Insert the new student into the database
    $stmt = $pdo->prepare('INSERT INTO students (name, email, phone) VALUES (:name, :email, :phone)');
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':email', $input['email']);
    $stmt->bindParam(':phone', $input['phone']);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Student created successfully'));
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Bad Request'));
}

// Handle PUT request
if (isset($input['id']) && isset($input['name']) && isset($input['email']) && isset($input['phone'])) {
    // Validate and sanitize the input data
    if (!preg_match('/^[a-zA-Z ]+$/', $input['name'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid name'));
        exit;
    }
    if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid email'));
        exit;
    }
    if (!preg_match('/^\d{10}$/', $input['phone'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid phone number'));
        exit;
    }

    // Update the existing student in the database
    $stmt = $pdo->prepare('UPDATE students SET name = :name, email = :email, phone = :phone WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':email', $input['email']);
    $stmt->bindParam(':phone', $input['phone']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Student updated successfully'));
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Bad Request'));
}

// Handle DELETE request
if (isset($input['id'])) {
    // Delete the student from the database
    $stmt = $pdo->prepare('DELETE FROM students WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Student deleted successfully'));
} elseif (isset($input['delete'])) {
    // Delete all students from the database
    $stmt = $pdo->prepare('DELETE FROM students');
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'All students deleted successfully'));
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Bad Request'));
}
?>