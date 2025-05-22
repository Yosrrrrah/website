<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

try {
    $conn = new mysqli("localhost", "root", "", "manel");
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Initialisez la variable $product
    $product = null;

    if (isset($_GET['id'])) {
        $product_id = intval($_GET['id']);
        $stmt = $conn->prepare("SELECT * FROM produits WHERE Id_P = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if (!$product) {
            echo "<div class='alert alert-danger'>Produit non trouvé.</div>";
            exit;
        }
    } else {
        echo "<div class='alert alert-danger'>ID de produit manquant.</div>";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $conn->real_escape_string($_POST['name']);
        $description = $conn->real_escape_string($_POST['description']);
        $price = floatval($_POST['price']);
        $image = $conn->real_escape_string($_POST['image']);
        $category_id = intval($_POST['category_id']);

        $sql = "UPDATE produits SET Name_P=?, Desc_P=?, Prix_P=?, Img_P=?, Id_Cat=? WHERE Id_P=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdssi", $name, $description, $price, $image, $category_id, $product_id);
        
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Produit modifié avec succès.</div>";
        } else {
            echo "<div class='alert alert-danger'>Erreur lors de la modification du produit.</div>";
        }
    }

    // Récupérer les catégories pour le formulaire
    $categories = $conn->query("SELECT * FROM categories");
    if (!$categories) {
        throw new Exception("Erreur lors de la récupération des catégories: " . $conn->error);
    }
} catch (Exception $e) {
    echo "Une erreur s'est produite : " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="NavBarAdmin.css">
    <title>Modifier Produit</title>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">Gaming Store</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add_product.php">Ajouter Produit</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view_products.php">Voir Produits</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Déconnexion</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Modifier un Produit</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($product['Name_P']) ? htmlspecialchars($product['Name_P']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" required><?php echo isset($product['Desc_P']) ? htmlspecialchars($product['Desc_P']) : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label for="price">Prix</label>
                <input type="number" class="form-control" id="price" name="price" value="<?php echo isset($product['Prix_P']) ? htmlspecialchars($product['Prix_P']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="image">Image URL</label>
                <input type="text" class="form-control" id="image" name="image" value="<?php echo isset($product['Img_P']) ? htmlspecialchars($product['Img_P']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="category_id">Catégorie</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <?php while ($category = $categories->fetch_assoc()) { ?>
                        <option value="<?php echo $category['Id_Cat']; ?>" <?php echo (isset($product['Id_Cat']) && $product['Id_Cat'] == $category['Id_Cat']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['Name_Cat']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Modifier</button>
            <a href="index.php" class="btn btn-secondary">Retour</a>
        </form>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-4">
        <p>&copy; 2025 Gaming Store. Tous droits réservés.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php 
$stmt->close(); 
$conn->close(); 
?>