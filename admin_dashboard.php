<?php
session_start();
// Check if the user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
$conn = new mysqli("localhost", "root", "", "manel");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Get user statistics
$total_users = 0;
$recent_users = [];
$sql_total = "SELECT COUNT(*) as total FROM users";
$result_total = $conn->query($sql_total);
if ($result_total && $row = $result_total->fetch_assoc()) {
    $total_users = $row['total'];
}
$sql_recent = "SELECT UserName, email FROM users";
$result_recent = $conn->query($sql_recent);
if ($result_recent) {
    while ($row = $result_recent->fetch_assoc()) {
        $recent_users[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Admin Dashboard - Gaming Store</title>
</head>
<body>
    <header class="bg-dark text-white text-center py-4">
        <h1>Admin Dashboard</h1>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="index.php">Gaming Store</a>
            <a class="navbar-brand" href="categories.php">Catégories</a>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?>
                <a class="navbar-brand" href="admin_dashboard.php">Admin Dashboard</a>
            <?php } ?>
            <a class="navbar-brand" href="logout.php">Déconnexion</a>
        </nav>
    </header>
    <main class="container mt-4">
        <h2 class="mb-4">User Statistics</h2>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Total Users</h5>
                <p class="card-text display-4"><?php echo $total_users; ?></p>
            </div>
        </div>
        <h3>Recent Registrations</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_users as $user) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['UserName']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <h2 class="mb-4 mt-5">Product Purchase Statistics</h2>
        <?php
        $sql_stats = "SELECT p.Name_P, COUNT(*) as total_purchased FROM purchases pu JOIN produits p ON pu.product_id = p.Id_P WHERE pu.status='pending' OR pu.status='completed' GROUP BY pu.product_id ORDER BY total_purchased DESC";
        $result_stats = $conn->query($sql_stats);
        ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Total Purchased</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_stats && $result_stats->num_rows > 0) {
                    while ($row = $result_stats->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['Name_P']); ?></td>
                            <td><?php echo $row['total_purchased']; ?></td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr><td colspan="2" class="text-center">No purchase data available</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </main>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>