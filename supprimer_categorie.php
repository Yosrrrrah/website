<?php
session_start();

// Inclure la connexion à la base de données
$conn = new mysqli("localhost", "root", "", "manel");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifiez si un ID de catégorie a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['category_id'])) {
    $category_id = $_POST['category_id'];

    // Préparer la requête de suppression
    $stmt = $conn->prepare("DELETE FROM categories WHERE Id_Cat = ?");
    $stmt->bind_param("i", $category_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Catégorie supprimée avec succès !</div>";
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de la suppression : " . $stmt->error . "</div>";
    }

    $stmt->close();
} else {
    echo "<div class='alert alert-warning'>Aucune catégorie spécifiée pour la suppression.</div>";
}

$conn->close();

// Rediriger vers la page des catégories après quelques secondes
header("refresh:2; url=categories.php");
?>