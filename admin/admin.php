<?php
session_start();
include('../db.php'); // Connexion à la base de données

// Vérifier si l'admin est connecté
if (!isset($_SESSION['admin_id'])) {
    echo "Accès refusé. Veuillez vous connecter en tant qu'admin.";
    exit;
}

// Traitement du formulaire d'ajout de vol
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $flight_code = $_POST['flight_code'];
    $departure = $_POST['departure'];
    $arrival = $_POST['arrival'];
    $flight_time = $_POST['flight_time'];
    $price = $_POST['price'];
    $class = $_POST['class'];
    $flight_date = $_POST['flight_date'];

    $stmt = $db->prepare("INSERT INTO flights (flight_code, departure, arrival, flight_time, price,class,flight_date) VALUES (?, ?, ?, ?, ?,?,?)");
    $stmt->execute([$flight_code, $departure, $arrival, $flight_time, $price,$class,$flight_date]);

    echo "<script>alert('Vol ajouté avec succès.'); window.location.href='admin.php';</script>";
}

// Récupérer tous les vols
$stmt = $db->query("SELECT * FROM flights ORDER BY id DESC");
$flights = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestion des vols</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center mb-4">🛫 Gestion des Vols</h1>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h2 class="card-title">✈️ Ajouter un Vol</h2>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Code du vol :</label>
                        <input type="text" class="form-control" name="flight_code" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Départ :</label>
                        <input type="text" class="form-control" name="departure" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Arrivée :</label>
                        <input type="text" class="form-control" name="arrival" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Heure de départ :</label>
                        <input type="datetime-local" class="form-control" name="flight_time" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Prix :</label>
                        <input type="number" class="form-control" name="price" required>
                    </div>
                    <div class="mb-3">
                        <select name="class" id="">
                            <option value="Business">Business</option>
                            <option value="Économie">Économique</option>
                            <option value="Première">Première</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" name="flight_date" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Ajouter le vol</button>
                </form>
            </div>
        </div>

        <h2 class="text-center mt-4">📋 Liste des Vols</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Code</th>
                        <th>Départ</th>
                        <th>Arrivée</th>
                        <th>Heure</th>
                        <th>Prix</th>
                        <th>Class</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($flights as $flight) : ?>
                        <tr>
                            <td><?= htmlspecialchars($flight['flight_code']) ?></td>
                            <td><?= htmlspecialchars($flight['departure']) ?></td>
                            <td><?= htmlspecialchars($flight['arrival']) ?></td>
                            <td><?= htmlspecialchars($flight['departure']) ?></td>
                            <td><?= htmlspecialchars($flight['price']) ?>€</td>
                            <td><?= htmlspecialchars($flight['class']) ?></td>
                            <td><?= htmlspecialchars($flight['flight_date']) ?></td>

                            <td>
                                <a href="delete_flight.php?id=<?= $flight['id'] ?>" class="btn btn-danger btn-sm">🗑️ Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <a href="logout.php" class="btn btn-secondary">🔒 Se Déconnecter</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>