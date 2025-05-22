<?php
session_start();
$conn = new mysqli("localhost", "root", "", "manel");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM categories";
$result = $conn->query($sql);

// Check if the query executed successfully
if (!$result) {
    die("Erreur lors de la récupération des catégories: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css"> <!-- Votre fichier CSS pour les styles -->
    <title>Catégories - Gaming Store</title>
</head>
<body>
    <header class="bg-dark text-white text-center py-4">
        <h1>Catégories</h1>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="index.php">Gaming Store</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <main class="container mt-4">
        <h2 class="text-center mb-4">Liste des Catégories</h2>
        <ul class="list-group">
            <?php if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) { ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="ProduitCat.php?category_id=<?php echo $row['Id_Cat']; ?>" class="btn btn-link">
                            <?php echo htmlspecialchars($row['Name_Cat']); ?>
                        </a>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?>
    <form action="supprimer_categorie.php" method="post" class="d-inline">
        <input type="hidden" name="category_id" value="<?php echo $row['Id_Cat']; ?>">
        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');">Supprimer</button>
    </form>
<?php } ?>
                       
                    </li>
                <?php }
            } else {
                echo "<li class='list-group-item'>Aucune catégorie trouvée</li>";
            }
            ?>
        </ul>
    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>