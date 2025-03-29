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
    // Récupérer les informations du vol
    $stmt = $db->prepare("SELECT * FROM flights WHERE id = :flight_id");
    $stmt->bindParam(':flight_id', $flight_id, PDO::PARAM_INT);
    $stmt->execute();
    $flight = $stmt->fetch(PDO::FETCH_ASSOC);

    // Récupérer les informations de l'utilisateur
    $user_id = $_SESSION['user_id'];
    $user_stmt = $db->prepare("SELECT * FROM users WHERE id = :user_id");
    $user_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $user_stmt->execute();
    $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

    if ($flight && $user) {
        // Insérer la réservation dans la base de données
        $insert_stmt = $db->prepare("INSERT INTO reservations (user_id, flight_id) VALUES (:user_id, :flight_id)");
        $insert_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $insert_stmt->bindParam(':flight_id', $flight_id, PDO::PARAM_INT);
        $insert_stmt->execute();

        if ($insert_stmt->rowCount() > 0) {
            // Redirection automatique après 5 secondes
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'list_reservations.php';
                    }, 5000);
                  </script>";
        }
    } else {
        echo "Informations manquantes pour le vol ou l'utilisateur.";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Réservation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f8f9fa;
            text-align: center;
            padding: 20px;
        }

        .ticket-container {
            background: white;
            padding: 20px;
            margin: auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            max-width: 500px;
            text-align: left;
        }

        .ticket-container h1 {
            color: #007bff;
            text-align: center;
        }

        .ticket-container p {
            margin: 10px 0;
            font-size: 16px;
        }

        .ticket-container strong {
            color: #333;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            margin: 10px;
            border: none;
            cursor: pointer;
        }

        .btn-print {
            background-color:rgb(16, 17, 16);
            color: white;
        }

        .btn-home {
            background-color: #007bff;
            color: white;
        }

        .btn:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>

    <div class="ticket-container">
        <h1>🎫 Ticket de Réservation</h1>
        <p><strong>Nom :</strong> <?php echo htmlspecialchars($user['nom']); ?></p>
        <p><strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Vol :</strong> <?php echo htmlspecialchars($flight['flight_code']); ?></p>
        <p><strong>Départ :</strong> <?php echo htmlspecialchars($flight['departure']); ?></p>
        <p><strong>Arrivée :</strong> <?php echo htmlspecialchars($flight['arrival']); ?></p>
        <p><strong>Heure de départ :</strong> <?php echo htmlspecialchars($flight['flight_time']); ?></p>
        <p><strong>Prix :</strong> <?php echo htmlspecialchars($flight['price']); ?>€</p>

        <button class="btn btn-print" onclick="window.print()">Imprimer le Ticket</button>
        <a href="list_reservations.php" class="btn btn-home">Voir mes Réservations</a>
    </div>

</body>
</html>
