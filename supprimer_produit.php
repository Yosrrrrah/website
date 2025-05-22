<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$message = '';
try {
    $conn = new mysqli("localhost", "root", "", "manel");
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    if (isset($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);
        $stmt = $conn->prepare("DELETE FROM produits WHERE Id_P = ?");
        $stmt->bind_param("i", $product_id);
        
        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Produit supprimé avec succès.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Erreur lors de la suppression du produit.</div>";
        }
    }
} catch (Exception $e) {
    $message = "<div class='alert alert-danger'>Une erreur s'est produite : " . $e->getMessage() . "</div>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Supprimer Produit</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">Gaming Store</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="add_product.php">Ajouter Produit</a></li>
                <li class="nav-item"><a class="nav-link" href="modifier_produit.php">Modifier Produit</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
            </ul>
        </div>
    </nav>
    <div class="container mt-4">
        <h2>Supprimer un Produit</h2>
        
        <?php if ($message): ?>
            <div class="alert-container"><?php echo $message; ?></div>
        <?php endif; ?>
        
       
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-4">
        <p>&copy; 2025 Gaming Store. Tous droits réservés.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>