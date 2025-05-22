<?php
session_start();

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "manel");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Traitement de la soumission du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Vérification pour l'administrateur
    $admin_stmt = $conn->prepare("SELECT * FROM users WHERE Email = ? AND role = 'admin'");
    $admin_stmt->bind_param("s", $email);
    $admin_stmt->execute();
    $admin_result = $admin_stmt->get_result();

    if ($admin = $admin_result->fetch_assoc()) {
        if ($password === $admin['Password']) {
            $_SESSION['Id_user'] = $admin['Id_user'];
            $_SESSION['UserName'] = $admin['Nom'];
            $_SESSION['role'] = 'admin'; 
            session_regenerate_id(true);
            header("Location: index.php");
            exit;
        } else {
            $error = "Identifiants incorrects.";
        }
    } else {
        // Vérification pour un utilisateur normal
        $stmt = $conn->prepare("SELECT * FROM users WHERE Email = ? AND role = 'user'");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if ($password === $user['Password']) {
                $_SESSION['Id_user'] = $user['Id_user'];
                $_SESSION['UserName'] = $user['Nom'];
                $_SESSION['role'] = 'user'; 
                session_regenerate_id(true);
                header("Location: index.php");
                exit;
            } else {
                $error = "Identifiants incorrects.";
            }
        } else {
            $error = "Identifiants incorrects."; 
        }
    }

    $stmt->close();
    $admin_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Connexion - Gaming Store</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Se connecter</h2>
        <form method="POST" action="">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
        <p class="mt-3"><a href="register.php">Pas encore de compte? Inscrivez-vous ici!</a></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>