<?php
session_start();
include('../db.php'); // Connexion à la base de données

// Vérifier si l'admin est connecté
if (!isset($_SESSION['admin_id'])) {
    echo "Accès refusé.";
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $db->prepare("DELETE FROM flights WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: admin.php"); // Redirection après suppression
exit;
?>
