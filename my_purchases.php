<?php
// Removed session and role checks to allow unrestricted access
$conn = new mysqli("localhost", "root", "", "manel");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_start();
// Handle delete action for logged-in users (remove purchase from DB)
if (isset($_POST['delete_purchase_id']) && isset($_SESSION['Id_user'])) {
    $delete_id = intval($_POST['delete_purchase_id']);
    $user_id = $_SESSION['Id_user'];
    $stmt_del = $conn->prepare("DELETE FROM purchases WHERE user_id = ? AND product_id = ?");
    $stmt_del->bind_param("ii", $user_id, $delete_id);
    $stmt_del->execute();
    $stmt_del->close();
    header('Location: my_purchases.php');
    exit();
}
// Handle delete action for guests (remove one product from the array)
if (isset($_POST['delete_guest_product_id'])) {
    $delete_id = intval($_POST['delete_guest_product_id']);
    if (isset($_SESSION['guest_product_ids'])) {
        $_SESSION['guest_product_ids'] = array_diff($_SESSION['guest_product_ids'], [$delete_id]);
        // Re-index array
        $_SESSION['guest_product_ids'] = array_values($_SESSION['guest_product_ids']);
    }
    header('Location: my_purchases.php');
    exit();
}
// If user is logged in, show their purchases; otherwise, show empty or message
$user_id = isset($_SESSION['Id_user']) ? $_SESSION['Id_user'] : null;
$guest_products = [];
if (!$user_id && isset($_SESSION['guest_product_ids']) && count($_SESSION['guest_product_ids']) > 0) {
    $ids = array_map('intval', $_SESSION['guest_product_ids']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));
    $stmt_product = $conn->prepare("SELECT * FROM produits WHERE Id_P IN ($placeholders)");
    $stmt_product->bind_param($types, ...$ids);
    $stmt_product->execute();
    $result_product = $stmt_product->get_result();
    while ($row = $result_product->fetch_assoc()) {
        $guest_products[] = $row;
    }
    $stmt_product->close();
}
if ($user_id) {
    $sql = "SELECT p.Id_P, p.Name_P, p.Prix_P, p.Img_P, pu.quantity, pu.status FROM purchases pu JOIN produits p ON pu.product_id = p.Id_P WHERE pu.user_id = ? ORDER BY pu.id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Purchases</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">My Purchases</h2>
    <?php if ($user_id && $result && $result->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><img src="<?php echo htmlspecialchars($row['Img_P']); ?>" alt="Product Image" width="60"></td>
                    <td><?php echo htmlspecialchars($row['Name_P']); ?></td>
                    <td><?php echo number_format($row['Prix_P'], 2); ?> TND</td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <form method="post" class="d-inline">
                            <input type="hidden" name="delete_purchase_id" value="<?php echo htmlspecialchars($row['Id_P']); ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php elseif ($user_id): ?>
        <div class="alert alert-info">You have not purchased any products yet.</div>
    <?php elseif (count($guest_products) > 0): ?>
        <div class="alert alert-info">You must log in to complete your purchase.</div>
        <div class="row">
        <?php foreach ($guest_products as $product): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <img src="<?php echo htmlspecialchars($product['Img_P']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['Name_P']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['Name_P']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($product['Desc_P']); ?></p>
                        <p class="card-text"><strong>Prix: <?php echo htmlspecialchars($product['Prix_P']); ?> TND</strong></p>
                        <a href="login.php" class="btn btn-primary mb-2">Se connecter pour acheter</a>
                        <form method="post" class="d-inline">
                            <input type="hidden" name="delete_guest_product_id" value="<?php echo htmlspecialchars($product['Id_P']); ?>">
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">Please log in to view your purchases.</div>
    <?php endif; ?>
    <a href="index.php" class="btn btn-secondary mt-3">Back to Store</a>
</div>
</body>
</html>
<?php
if (isset($stmt)) $stmt->close();
$conn->close();
?>