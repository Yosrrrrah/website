<?php
$conn = new mysqli("localhost", "root", "amine", "molka");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the category ID from the URL
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

// Prepare SQL statement
$sql = "SELECT * FROM produits WHERE Id_Cat = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Produits - Gaming Store</title>
</head>
<body>
    <header class="bg-dark text-white text-center py-4">
        <h1>Produits</h1>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="index.php">Gaming Store</a>
            <a class="navbar-brand" href="categories.php">Catégories</a>
        </nav>
    </header>
    <main class="container mt-4">
        <h2 class="text-center mb-4">Produits dans cette Catégorie</h2>
        <ul class="list-group">
            <?php if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) { ?>
                    <li class="list-group-item">
                        <h5><?php echo htmlspecialchars($row['Name_P']); ?></h5>
                        <p><?php echo htmlspecialchars($row['Desc_P']); ?></p>
                        <p>Prix: <?php echo htmlspecialchars($row['Prix_P']); ?> TND</p>
                        <img src="<?php echo htmlspecialchars($row['Img_P']); ?>" alt="<?php echo htmlspecialchars($row['Name_P']); ?>" style="width: 100px;">
                    </li>
                <?php }
            } else {
                echo "<li class='list-group-item'>Aucun produit trouvé dans cette catégorie</li>";
            }
            ?>
        </ul>
    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>