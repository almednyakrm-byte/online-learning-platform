<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get user role
$user_role = $_SESSION['user_role'];

// Get input data
$input_data = json_decode(file_get_contents('php://input'), true);

// Function to validate input data
function validate_input($data) {
    $errors = array();
    if (!isset($data['name']) || empty($data['name'])) {
        $errors[] = 'Name is required';
    }
    if (!isset($data['email']) || empty($data['email'])) {
        $errors[] = 'Email is required';
    }
    if (!isset($data['phone']) || empty($data['phone'])) {
        $errors[] = 'Phone is required';
    }
    return $errors;
}

// Function to sanitize input data
function sanitize_input($data) {
    $sanitized_data = array();
    $sanitized_data['name'] = trim($data['name']);
    $sanitized_data['email'] = trim($data['email']);
    $sanitized_data['phone'] = trim($data['phone']);
    return $sanitized_data;
}

// GET all instructors
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    try {
        $stmt = $pdo->prepare('SELECT * FROM instructors');
        $stmt->execute();
        $instructors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($instructors);
        http_response_code(200);
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Database error'));
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'get_one') {
    try {
        $stmt = $pdo->prepare('SELECT * FROM instructors WHERE id = :id');
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        $instructor = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($instructor) {
            header('Content-Type: application/json');
            echo json_encode($instructor);
            http_response_code(200);
        } else {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'Instructor not found'));
        }
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Database error'));
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'get_by_email') {
    try {
        $stmt = $pdo->prepare('SELECT * FROM instructors WHERE email = :email');
        $stmt->bindParam(':email', $_GET['email']);
        $stmt->execute();
        $instructor = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($instructor) {
            header('Content-Type: application/json');
            echo json_encode($instructor);
            http_response_code(200);
        } else {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'Instructor not found'));
        }
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Database error'));
    }
}

// POST create instructor
elseif (isset($_POST['action']) && $_POST['action'] == 'create') {
    if ($user_role != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    $errors = validate_input($input_data);
    if ($errors) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(array('errors' => $errors));
        exit;
    }
    $sanitized_data = sanitize_input($input_data);
    try {
        $stmt = $pdo->prepare('INSERT INTO instructors (name, email, phone) VALUES (:name, :email, :phone)');
        $stmt->bindParam(':name', $sanitized_data['name']);
        $stmt->bindParam(':email', $sanitized_data['email']);
        $stmt->bindParam(':phone', $sanitized_data['phone']);
        $stmt->execute();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Instructor created successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Database error'));
    }
}

// PUT update instructor
elseif (isset($_PUT['action']) && $_PUT['action'] == 'update') {
    if ($user_role != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    $errors = validate_input($input_data);
    if ($errors) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(array('errors' => $errors));
        exit;
    }
    $sanitized_data = sanitize_input($input_data);
    try {
        $stmt = $pdo->prepare('UPDATE instructors SET name = :name, email = :email, phone = :phone WHERE id = :id');
        $stmt->bindParam(':id', $_PUT['id']);
        $stmt->bindParam(':name', $sanitized_data['name']);
        $stmt->bindParam(':email', $sanitized_data['email']);
        $stmt->bindParam(':phone', $sanitized_data['phone']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Instructor updated successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Database error'));
    }
}

// DELETE instructor
elseif (isset($_DELETE['action']) && $_DELETE['action'] == 'delete') {
    if ($user_role != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    try {
        $stmt = $pdo->prepare('DELETE FROM instructors WHERE id = :id');
        $stmt->bindParam(':id', $_DELETE['id']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Instructor deleted successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Database error'));
    }
}

// Default response
http_response_code(404);
header('Content-Type: application/json');
echo json_encode(array('error' => 'Not found'));