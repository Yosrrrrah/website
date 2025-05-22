<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['Id_user'])) {
    header("Location: login.php"); // Rediriger vers la page de connexion si non connecté
    exit;
}

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "manel");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les informations de l'utilisateur
$user_id = $_SESSION['Id_user']; // Assurez-vous que cela correspond à ce qui est défini lors de la connexion
$sql = "SELECT UserName, email FROM users WHERE Id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Vérifier si l'utilisateur a été trouvé
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Utilisateur non trouvé.";
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Profil - Gaming Store</title>
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
        <h1>Mon Profil</h1>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Gaming Store</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                    <?php if (!isset($_SESSION['role'])) { ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Connexion</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php">Inscription</a></li>
                    <?php } ?>
                    <?php if (isset($_SESSION['role'])) { ?>
                        <li class="nav-item"><a class="nav-link" href="Favoris.php">Favoris</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
                    <?php } ?>
                </ul>
            </div>
        </nav>
    </header>

    <div class="container mt-4 content">
        <div class="card">
            <div class="card-body d-flex align-items-center">
                <div class="mr-3">
                    <img src="./img/admin.webp" alt="Photo de profil" class="img-fluid rounded-circle" style="width: 100px; height: 100px;">
                </div>
                <div>
                    <h5 class="card-title">Informations de l'utilisateur</h5>
                    <p class="card-text"><strong>Nom :</strong> <?php echo htmlspecialchars($user['UserName']); ?></p>
                    <p class="card-text"><strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-4">
        <p>&copy; 2023 Gaming Store. Tous droits réservés.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>