<?php
session_start();

// Afficher les erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "manel");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['Id_user'])) {
    die("Utilisateur non connecté.");
}

// Récupérer les favoris de l'utilisateur
$userId = $_SESSION['Id_user'];
$sql = "SELECT p.* FROM favorites f JOIN produits p ON f.Id_P = p.Id_P WHERE f.Id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Vérifiez si la requête a échoué
if (!$result) {
    die("Erreur lors de l'exécution de la requête : " . $conn->error);
}

// Traitement de la suppression d'un produit des favoris
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_id'])) {
    $removeId = intval($_POST['remove_id']);
    $removeStmt = $conn->prepare("DELETE FROM favorites WHERE Id_P = ? AND Id_user = ?");
    $removeStmt->bind_param("ii", $removeId, $userId);
    
    if ($removeStmt->execute()) {
        header("Location: favoris.php?success=Produit retiré des favoris !");
        exit();
    } else {
        echo "Erreur lors de la suppression des favoris : " . $removeStmt->error;
    }
    
    $removeStmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Mes Favoris - Gaming Store</title>
    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        .content {
            flex: 1; /* Prend tout l'espace disponible */
        }
    </style>
</head>
<body>
    <header class="bg-dark text-white text-center py-4">
        <h1>Mes Favoris</h1>
    </header>

    <main class="container mt-4 content"> <!-- Ajout de la classe 'content' -->
        <h2 class="text-center mb-4">Vos produits favoris</h2>
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="<?= htmlspecialchars($row['Img_P']); ?>" class="card-img-top" alt="<?= htmlspecialchars($row['Name_P']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($row['Name_P']); ?></h5>
                                <p class="card-text"><?= htmlspecialchars($row['Desc_P']); ?></p>
                                <p class="card-text"><strong>Prix : <?= htmlspecialchars($row['Prix_P']); ?> €</strong></p>
                                <form method="POST" action="">
                                    <input type="hidden" name="remove_id" value="<?= htmlspecialchars($row['Id_P']); ?>">
                                    <button type="submit" class="btn btn-danger">Retirer des favoris</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">Vous n'avez aucun favori.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2023 Gaming Store. Tous droits réservés.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>