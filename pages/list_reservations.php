<?php
session_start();
include('../db.php');  

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // Récupérer les réservations de l'utilisateur avec les détails du vol
    $stmt = $db->prepare("SELECT * FROM reservations r JOIN flights f ON r.flight_id = f.id WHERE r.user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color:rgb(210, 224, 238);
            text-align: center;
            padding: 20px;
        }

        h1 {
            color: #007bff;
            margin-bottom: 20px;
        }

        .container {
            width: 80%;
            margin: auto;
        }
        .reservation-card {
            background:rgb(143, 186, 232);
            padding: 20px;
            margin: 10px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: left;
            max-width: 500px;
        }

        .reservation-card p {
            margin: 5px 0;
        }

        .reservation-card strong {
            color: #333;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            margin: 10px;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
            border: none;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            border: none;
        }

        .btn:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>

    <h1>Mes Réservations ✈️</h1>

    <div class="container">
        <?php if ($reservations): ?>
            <?php foreach ($reservations as $reservation): ?>
                <div class="reservation-card">
                    <p><strong>Vol :</strong> <?php echo htmlspecialchars($reservation['flight_code']); ?></p>
                    <p><strong>Départ :</strong> <?php echo htmlspecialchars($reservation['departure']); ?></p>
                    <p><strong>Arrivée :</strong> <?php echo htmlspecialchars($reservation['arrival']); ?></p>
                    <p><strong>Heure de départ :</strong> <?php echo htmlspecialchars($reservation['flight_time']); ?></p>
                    <p><strong>Prix :</strong> <?php echo htmlspecialchars($reservation['price']); ?>€</p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Vous n'avez pas encore de réservations.</p>
        <?php endif; ?>
    </div>

    <!-- Boutons de navigation -->
    <a href="profil.php" class="btn btn-primary">⬅️ Retour au Profil</a>
    <a href="../pages/deconnexion.php" class="btn btn-secondary">🔒 Se Déconnecter</a>

</body>
</html>
