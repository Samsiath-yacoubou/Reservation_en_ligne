<!-- <?php
session_start();
include('../db.php');

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Sauvegarder la page de réservation dans la session
    $_SESSION['redirect_after_login'] = "reserve.php?flight_id=" . $_GET['flight_id'];
    
    // Rediriger vers la page de connexion
    header("Location: connexion.php");
    exit;
}

try {
    // Récupérer les informations du vol
    $stmt = $db->prepare("SELECT * FROM flights WHERE id = :flight_id");
    $stmt->bindParam(':flight_id', $flight_id, PDO::PARAM_INT);
    $stmt->execute();
    $flight = $stmt->fetch(PDO::FETCH_ASSOC);

    // Récupérer les informations de l'utilisateur connecté
    $user_id = $_SESSION['user_id'];
    $user_stmt = $db->prepare("SELECT * FROM users WHERE id = :user_id");
    $user_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $user_stmt->execute();
    $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

    // Générer le ticket
    if ($flight && $user) {
        // Affichage du ticket
        echo "<h1>Ticket de Réservation</h1>";
        echo "<p><strong>Nom :</strong> " . htmlspecialchars($user['nom']) . "</p>";
        echo "<p><strong>Email :</strong> " . htmlspecialchars($user['email']) . "</p>";
        echo "<p><strong>Vol :</strong> " . htmlspecialchars($flight['flight_code']) . "</p>";
        echo "<p><strong>Départ :</strong> " . htmlspecialchars($flight['departure']) . "</p>";
        echo "<p><strong>Arrivée :</strong> " . htmlspecialchars($flight['arrival']) . "</p>";
        echo "<p><strong>Heure de départ :</strong> " . htmlspecialchars($flight['flight_time']) . "</p>";
        echo "<p><strong>Prix :</strong> " . htmlspecialchars($flight['price']) . "€</p>";

        // Bouton pour imprimer le ticket
        echo "<button onclick='window.print()'>Imprimer le Ticket</button>";

        // Ajouter la réservation dans la base de données
        $insert_stmt = $db->prepare("INSERT INTO reservations (user_id, flight_id) VALUES (:user_id, :flight_id)");
        $insert_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $insert_stmt->bindParam(':flight_id', $flight_id, PDO::PARAM_INT);
        $insert_stmt->execute();

        // Redirection vers la liste des vols après 5 secondes
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'list_reservations.php'; // Remplace par la page de la liste des réservations
                }, 5000);
              </script>";
    } else {
        echo "Informations manquantes pour le vol ou l'utilisateur.";
    }
} catch (PDOException $e) {
    // Gestion des erreurs PDO
    echo "Erreur : " . $e->getMessage();
}
?> -->
