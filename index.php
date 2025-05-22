<?php
session_start();

try {
    $conn = new mysqli("localhost", "root", "", "manel");
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    if (isset($_GET['search'])) {
        $searchTerm = $conn->real_escape_string($_GET['search']);
        $sql = "SELECT * FROM produits WHERE Name_P LIKE '%$searchTerm%'";
    } else {
        $sql = "SELECT * FROM produits";
    }

    $result = $conn->query($sql);
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
    <link rel="stylesheet" href="./css/styleIndex.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Accueil - Gaming Store</title>
</head>
<body>
    <header class="bg-dark text-white text-center py-4">
        <h1>Bienvenue au Gaming Store</h1>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Gaming Store</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a class="nav-link" href="categories.php">Catégories</a></li>
                    <?php if (!isset($_SESSION['role'])) { ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Connexion</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php">Inscription</a></li>
                    <?php } ?>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?>
                        <li class="nav-item"><a class="nav-link" href="ajout_produit.php">Ajouter Produit</a></li>
                        <li class="nav-item"><a class="nav-link" href="ajout_categories.php">Ajouter Categories</a></li>
                        <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Admin Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
                        <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                    <?php } ?>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user') { ?>
                        <li class="nav-item"><a class="nav-link" href="Favoris.php">Favoris</a></li>
                        <li class="nav-item"><a class="nav-link" href="my_purchases.php">Mes Achats</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
                        <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                    <?php } ?>
                </ul>
                <form class="form-inline my-2 my-lg-0" method="get" action="">
                    <input class="form-control mr-sm-2" type="search" name="search" placeholder="Rechercher un produit" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Recherche</button>
                </form>
            </div>
        </nav>
    </header>

    <main class="container mt-4">
        <h2 class="text-center mb-4">Nouveaux Produits</h2>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Image</th>
                        <th scope="col">Nom du Produit</th>
                        <th scope="col">Prix</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><img src="<?php echo htmlspecialchars($row['Img_P']); ?>" alt="<?php echo htmlspecialchars($row['Name_P']); ?>" style="width: 50px; height: auto;"></td>
                                <td><?php echo htmlspecialchars($row['Name_P']); ?></td>
                                <td><?php echo htmlspecialchars($row['Prix_P']); ?> TND</td>
                                <td>
                                    <a href="modifier_produit.php?id=<?php echo $row['Id_P']; ?>" class="btn btn-secondary btn-sm">Modifier</a>
                                    <form action="supprimer_produit.php" method="post" class="d-inline">
                                        <input type="hidden" name="product_id" value="<?php echo $row['Id_P']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>Aucun produit trouvé</td></tr>";
                    } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="row">
                <?php if ($result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) { ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="<?php echo htmlspecialchars($row['Img_P']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['Name_P']); ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($row['Name_P']); ?></h5>
                                    <p class="card-text"><strong>Prix: <?php echo htmlspecialchars($row['Prix_P']); ?> TND</strong></p>
                                    <a href="product.php?id=<?php echo $row['Id_P']; ?>" class="btn btn-primary">Voir Détails</a>
                                    
                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user') { ?>
                                        <form action="ajouter_favoris.php" method="post" class="mt-2">
                                            <input type="hidden" name="product_id" value="<?php echo $row['Id_P']; ?>">
                                            <button type="submit" class="btn btn-warning">Ajouter aux Favoris</button>
                                        </form>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php }
                } else {
                    echo "<p class='text-center'>Aucun produit trouvé</p>";
                } ?>
            </div>
        <?php } ?>
    </main>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2023 Gaming Store. Tous droits réservés.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>