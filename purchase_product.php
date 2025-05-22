<?php
session_start();
if (!isset($_SESSION['Id_user'])) {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    if ($product_id > 0) {
        if (!isset($_SESSION['guest_product_ids'])) {
            $_SESSION['guest_product_ids'] = [];
        }
        if (!in_array($product_id, $_SESSION['guest_product_ids'])) {
            $_SESSION['guest_product_ids'][] = $product_id;
        }
        // Insert guest purchase into the database
        $conn = new mysqli("localhost", "root", "", "manel");
        if (!$conn->connect_error) {
            $stmt = $conn->prepare("INSERT INTO purchases (user_id, product_id, quantity, status) VALUES (NULL, ?, 1, 'pending')");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $stmt->close();
            $conn->close();
        }
    }
    header('Location: my_purchases.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $user_id = $_SESSION['Id_user'];
    $product_id = intval($_POST['product_id']);
    $quantity = isset($_POST['quantity']) ? max(1, intval($_POST['quantity'])) : 1;
    $conn = new mysqli("localhost", "root", "", "manel");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $stmt = $conn->prepare("INSERT INTO purchases (user_id, product_id, quantity, status) VALUES (?, ?, ?, 'pending')");
    $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    if ($stmt->execute()) {
        $_SESSION['purchase_success'] = true;
        header('Location: my_purchases.php');
        exit();
    } else {
        $_SESSION['purchase_error'] = true;
        header('Location: product.php?id=' . $product_id . '&purchase=error');
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}