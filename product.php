<?php
// Démarrer la session si nécessaire
session_start();

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "manel");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sécuriser l'ID du produit
$product_id = intval($_GET['id']); // Utiliser intval pour sécuriser

// Utiliser une requête préparée
$stmt = $conn->prepare("SELECT * FROM produits WHERE Id_P = ?");
$stmt->bind_param("i", $product_id); // 'i' pour integer
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "Produit non trouvé.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styleProduct.css">
    <title><?php echo htmlspecialchars($product['Name_P']); ?> - Gaming Store</title>
</head>
<body>
    <header class="bg-dark text-white text-center py-4">
        <h1><?php echo htmlspecialchars($product['Name_P']); ?></h1>
        <nav>
            <a class="text-white" href="index.php">Accueil</a>
            <a class="text-white ml-3" href="categories.php">Catégories</a>
        </nav>
    </header>
    <main class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                
                <img  src=<?php echo htmlspecialchars($product['Img_P']); ?> class="fixed-img"  alt="<?php echo htmlspecialchars($product['Name_P']); ?>" >
                
                        <h2 class="card-title">Détails du produit</h2>
                        <p class="card-text"><?php echo htmlspecialchars($product['Desc_P']); ?></p>
                        <p class="font-weight-bold">Prix: <?php echo htmlspecialchars($product['Prix_P']); ?> TND</p>
                        <?php if (isset($_SESSION['role'])) { ?>
                            <form method="POST" action="ajouter_favoris.php">
                                <input type="hidden" name="Id_P" value="<?php echo $product['Id_P']; ?>">
                                <button type="submit" class="btn btn-warning">Ajouter aux Favoris</button>
                            </form>
                            <form method="POST" action="purchase_product.php" class="mt-2">
                                <input type="hidden" name="product_id" value="<?php echo $product['Id_P']; ?>">
                                <input type="number" name="quantity" value="1" min="1" class="form-control mb-2" style="width:100px;display:inline-block;">
                                <button type="submit" class="btn btn-success">Acheter</button>
                            </form>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php 
$stmt->close(); // Fermer la requête préparée
$conn->close(); 
?>



