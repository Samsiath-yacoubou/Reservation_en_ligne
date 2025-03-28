<?php
session_start();
include('../db.php');  

if (!isset($_SESSION['user_id'])) {
    echo "Veuillez vous connecter pour voir vos réservations.";
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    
    $stmt = $db->prepare("SELECT * FROM reservations r JOIN flights f ON r.flight_id = f.id WHERE r.user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($reservations) {
        echo "<h1>Mes Réservations</h1>";
        foreach ($reservations as $reservation) {
            echo "<p><strong>Vol :</strong> " . htmlspecialchars($reservation['flight_code']) . "</p>";
            echo "<p><strong>Départ :</strong> " . htmlspecialchars($reservation['departure']) . "</p>";
            echo "<p><strong>Arrivée :</strong> " . htmlspecialchars($reservation['arrival']) . "</p>";
            echo "<p><strong>Heure de départ :</strong> " . htmlspecialchars($reservation['flight_time']) . "</p>";
            echo "<p><strong>Prix :</strong> " . htmlspecialchars($reservation['price']) . "€</p><hr>";
        }
    } else {
        echo "Vous n'avez pas de réservations.";
    }
} catch (PDOException $e) {
    
    echo "Erreur : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div class="text-center mt-4">
            <a href="../pages/deconnexion.php" class="btn btn-secondary">🔒 Se Déconnecter</a>
        </div>
</body>
</html>