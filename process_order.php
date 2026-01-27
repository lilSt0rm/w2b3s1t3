<?php
require_once 'config/database.php';
require_once 'includes/auth.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getConnection();
    
    // Generate order reference
    $order_reference = 'CMD-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    
    // Handle file upload
    $uploaded_files = [];
    if (!empty($_FILES['print_files'])) {
        $upload_dir = 'uploads/' . $_SESSION['user_id'] . '/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        foreach ($_FILES['print_files']['tmp_name'] as $key => $tmp_name) {
            $filename = basename($_FILES['print_files']['name'][$key]);
            $target_file = $upload_dir . time() . '_' . $filename;
            
            if (move_uploaded_file($tmp_name, $target_file)) {
                $uploaded_files[] = $target_file;
            }
        }
    }
    
    // Prepare order data
    $order_data = [
        'order_reference' => $order_reference,
        'user_id' => $_SESSION['user_id'],
        'product_type' => $_POST['product_type'] ?? '',
        'quantity' => $_POST['quantity'] ?? 0,
        'format' => $_POST['format'] ?? '',
        'paper_type' => $_POST['paper_type'] ?? '',
        'deadline' => $_POST['deadline'] ?? '',
        'color_mode' => $_POST['color_mode'] ?? '',
        'sides' => $_POST['sides'] ?? '',
        'finishings' => json_encode($_POST['finishings'] ?? []),
        'files' => json_encode($uploaded_files),
        'notes' => $_POST['notes'] ?? '',
        'estimated_budget' => $_POST['estimated_budget'] ?? 0,
        'status' => 'pending'
    ];
    
    // Insert order
    $stmt = $conn->prepare("
        INSERT INTO orders (
            order_reference, user_id, product_type, quantity, format, 
            paper_type, deadline, color_mode, sides, finishings, 
            files, notes, estimated_budget, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $success = $stmt->execute(array_values($order_data));
    
    if ($success) {
        // Log activity
        $stmt = $conn->prepare("
            INSERT INTO activities (user_id, activity_type, description)
            VALUES (?, 'new_order', ?)
        ");
        $stmt->execute([
            $_SESSION['user_id'],
            'Nouvelle commande créée: ' . $order_reference
        ]);
        
        header('Location: dashboard.php?success=order_created');
        exit();
    } else {
        header('Location: order.php?error=order_failed');
        exit();
    }
}
?>