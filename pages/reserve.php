<?php
session_start();
include('../db.php');


if (!isset($_SESSION['user_id'])) {
    
    $_SESSION['redirect_after_login'] = "reserve.php?flight_id=" . $_GET['flight_id'];
    
    
    header("Location: connexion.php");
    exit;
}


if (isset($_GET['flight_id']) && is_numeric($_GET['flight_id'])) {
    $flight_id = (int) $_GET['flight_id'];
} else {
    die("ID du vol invalide.");
}

try {
    
    $stmt = $db->prepare("SELECT * FROM flights WHERE id = :flight_id");
    $stmt->bindParam(':flight_id', $flight_id, PDO::PARAM_INT);
    $stmt->execute();
    $flight = $stmt->fetch(PDO::FETCH_ASSOC);

    
    $user_id = $_SESSION['user_id'];
    $user_stmt = $db->prepare("SELECT * FROM users WHERE id = :user_id");
    $user_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $user_stmt->execute();
    $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

    
    if ($flight && $user) {
        
        echo "<h1>Ticket de Réservation</h1>";
        echo "<p><strong>Nom :</strong> " . htmlspecialchars($user['nom']) . "</p>";
        echo "<p><strong>Email :</strong> " . htmlspecialchars($user['email']) . "</p>";
        echo "<p><strong>Vol :</strong> " . htmlspecialchars($flight['flight_code']) . "</p>";
        echo "<p><strong>Départ :</strong> " . htmlspecialchars($flight['departure']) . "</p>";
        echo "<p><strong>Arrivée :</strong> " . htmlspecialchars($flight['arrival']) . "</p>";
        echo "<p><strong>Heure de départ :</strong> " . htmlspecialchars($flight['flight_time']) . "</p>";
        echo "<p><strong>Prix :</strong> " . htmlspecialchars($flight['price']) . "€</p>";

        
        echo "<button onclick='window.print()'>Imprimer le Ticket</button>";

        
        $insert_stmt = $db->prepare("INSERT INTO reservations (user_id, flight_id) VALUES (:user_id, :flight_id)");
        $insert_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $insert_stmt->bindParam(':flight_id', $flight_id, PDO::PARAM_INT);
        $insert_stmt->execute();

        
        if ($insert_stmt->rowCount() > 0) {
            
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'list_reservations.php'; // Remplace par la page de la liste des réservations
                    }, 5000);
                  </script>";
        } else {
            echo "Erreur lors de l'ajout de la réservation.";
        }
    } else {
        echo "Informations manquantes pour le vol ou l'utilisateur.";
    }
} catch (PDOException $e) {
    
    echo "Erreur : " . $e->getMessage();
}
?>
