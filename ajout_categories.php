<?php
// Démarrer la session
session_start();

// Inclure la connexion à la base de données
$conn = new mysqli("localhost", "root", "", "manel");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifiez si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_categorie = $_POST['nom_categorie'];

    // Validation simple
    if (!empty($nom_categorie)) {
        // Préparer la requête d'insertion
        $stmt = $conn->prepare("INSERT INTO categories (Name_Cat) VALUES (?)");
        $stmt->bind_param("s", $nom_categorie);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Catégorie ajoutée avec succès !</div>";
        } else {
            echo "<div class='alert alert-danger'>Erreur : " . $stmt->error . "</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='alert alert-warning'>Veuillez entrer un nom de catégorie.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Ajouter une Catégorie - Gaming Store</title>
</head>
<body>
    <header class="bg-dark text-white text-center py-4">
        <h1>Ajouter une Catégorie</h1>
    </header>
    
    <main class="container mt-4">
        <h2 class="text-center mb-4">Formulaire d'Ajout de Catégorie</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="nom_categorie">Nom de la Catégorie :</label>
                <input type="text" class="form-control" id="nom_categorie" name="nom_categorie" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <a href="categories.php" class="btn btn-secondary">Retour à la liste des catégories</a>
        </form>
    </main>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 Gaming Store. Tous droits réservés.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>