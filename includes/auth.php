<?php
// Authentication functions

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

function logout() {
    session_destroy();
    header('Location: login.php');
    exit();
}

// User functions
function getUserById($id) {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function loginUser($email, $password) {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_email'] = $user['email'];
        return true;
    }
    return false;
}

function registerUser($data) {
    $conn = getConnection();
    $stmt = $conn->prepare("
        INSERT INTO users (full_name, email, phone, password, company, address, subcontractor_type) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    
    return $stmt->execute([
        $data['full_name'],
        $data['email'],
        $data['phone'],
        $hashedPassword,
        $data['company'] ?? '',
        $data['address'] ?? '',
        $data['subcontractor_type'] ?? ''
    ]);
}
?>