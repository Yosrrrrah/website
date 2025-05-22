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

// Vérifiez si l'utilisateur est connecté et si le produit existe
if (isset($_SESSION['Id_user']) && isset($_POST['product_id'])) { // Utiliser 'Id_user'
    $userId = $_SESSION['Id_user'];
    $productId = intval($_POST['product_id']);

    // Vérifiez si le produit existe
    $checkProduct = $conn->prepare("SELECT Id_P FROM produits WHERE Id_P = ?");
    $checkProduct->bind_param("i", $productId);
    $checkProduct->execute();
    $result = $checkProduct->get_result();

    if ($result->num_rows > 0) {
        // Insertion dans la table favorites
        $stmt = $conn->prepare("INSERT INTO favorites (Id_P, Id_user) VALUES (?, ?)");
        $stmt->bind_param("ii", $productId, $userId);

        if ($stmt->execute()) {
            header("Location: index.php?success=Produit ajouté aux favoris !");
            exit();
        } else {
            echo "Erreur lors de l'ajout aux favoris : " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Le produit n'existe pas.";
    }

    $checkProduct->close();
} else {
    echo "Utilisateur non connecté ou ID de produit manquant.";
}

$conn->close();
?>