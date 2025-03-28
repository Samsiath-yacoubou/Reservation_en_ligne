<?php
session_start();
include '../db.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$search_results = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $departure = $_POST['departure'];
    $arrival = $_POST['arrival'];
    $date = $_POST['date'];
    $class = $_POST['class'];

    

    $sql = "SELECT * FROM flights WHERE departure LIKE ? AND arrival LIKE ? AND flight_date = ? AND class LIKE ?";
    $stmt = $db->prepare($sql);
    $stmt->execute(["%$departure%", "%$arrival%", "$date", "%$class%"]);
    $search_results = $stmt->fetchAll(); 
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Recherche de Vol</title>
    <style>
        /* Ajoute ton style ici */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f8f9fa;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .booking__nav {
            max-width: 600px;
            margin: auto;
            display: flex;
            background-color: rgb(181, 211, 248);
            border-radius: 5px;
            overflow: hidden;
        }

        .booking__nav span {
            flex: 1;
            padding: 1rem;
            text-align: center;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .booking__nav span.active {
            background-color: #007bff;
            color: white;
        }

        .booking__container {
            border-radius: 2rem;
            border: 1px solid rgb(181, 211, 248);
            box-shadow: 5px 5px 30px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            padding: 20px;
        }

        .search-box {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 20px;
        }

        input {
            padding: 10px;
            width: 200px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn-search {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
        }

        .btn-search:hover {
            opacity: 0.8;
        }

        .results {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
            text-align: center;
        }

        th,
        td {
            padding: 10px;
        }

        th {
            background: #007bff;
            color: white;
        }

        .btn-reserve {
            background: #28a745;
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 5px;
        }

        .btn-reserve:hover {
            opacity: 0.8;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Bienvenue sur votre Tableau de Bord</h1>

        
        <form method="POST" action="">
            <div class="booking__container">
                <div class="search-box">
                    <select name="class" id="">
                        <option value="Business">Business</option>
                        <option value="Économie">Économique</option>
                        <option value="Première">Première</option>
                    </select>
                    <input type="text" name="departure" placeholder="Ville de départ" required>
                    <input type="text" name="arrival" placeholder="Ville d'arrivée" required>
                    <input type="date" name="date" required>
                    <button class="btn-search" type="submit">Rechercher</button>
                </div>
            </div>
        </form>

        
        <div class="results">
            <h2>Résultats de la recherche</h2>
            <?php if ($search_results): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Vol</th>
                            <th>Départ</th>
                            <th>Arrivée</th>
                            <th>Heure</th>
                            <th>Prix</th>
                            <th>Classe</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($search_results as $flight): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($flight['flight_code']); ?></td>
                                <td><?php echo htmlspecialchars($flight['departure']); ?></td>
                                <td><?php echo htmlspecialchars($flight['arrival']); ?></td>
                                <td><?php echo htmlspecialchars($flight['flight_time']); ?></td>
                                <td><?php echo htmlspecialchars($flight['price']); ?>€</td>
                                <td><?php echo htmlspecialchars($flight['class']); ?></td>
                                <td>
                                    <a href="reserve.php?flight_id=<?php echo $flight['id']; ?>" class="btn-reserve">Réserver</a>
                                    <a href="ticket.php?flight_id=<?php echo $flight['id']; ?>" class="btn-ticket">Ticket</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Aucun vol trouvé pour ces critères.</p>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>
