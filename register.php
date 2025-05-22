<?php
session_start();

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "manel");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Traitement de la soumission du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['nom']; // Utilisation de UserName
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; // Pas de hachage

    // Insertion de l'utilisateur dans la base de données
    $stmt = $conn->prepare("INSERT INTO users (UserName, Password, email, role) VALUES (?, ?, ?, 'user')");
    $stmt->bind_param("sss", $username, $password, $email);
    
    if ($stmt->execute()) {
        echo "<div class='alert alert-success' role='alert'>Inscription réussie !</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Erreur : " . $stmt->error . "</div>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Inscription - Gaming Store</title>
</head>
<body>
    <div class="container mt-5">
        <h2>S'inscrire</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>
        <p class="mt-3"><a href="login.php">Déjà inscrit? Connectez-vous ici!</a></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>