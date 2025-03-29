<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Récupération des options uniques pour les listes déroulantes
$departures = $db->query("SELECT DISTINCT departure FROM flights")->fetchAll(PDO::FETCH_COLUMN);
$arrivals = $db->query("SELECT DISTINCT arrival FROM flights")->fetchAll(PDO::FETCH_COLUMN);
$classes = $db->query("SELECT DISTINCT class FROM flights")->fetchAll(PDO::FETCH_COLUMN);
$dates = $db->query("SELECT DISTINCT flight_date FROM flights ORDER BY flight_date ASC")->fetchAll(PDO::FETCH_COLUMN);

// Récupération des vols selon la recherche
$search_results = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $departure = !empty($_POST['departure']) ? $_POST['departure'] : "%";
    $arrival = !empty($_POST['arrival']) ? $_POST['arrival'] : "%";
    $date = !empty($_POST['date']) ? $_POST['date'] : "%";
    $class = !empty($_POST['class']) ? $_POST['class'] : "%";

    $sql = "SELECT * FROM flights WHERE departure LIKE ? AND arrival LIKE ? AND flight_date LIKE ? AND class LIKE ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$departure, $arrival, $date, $class]);
    $search_results = $stmt->fetchAll();
} else {
    // Afficher tous les vols par défaut
    $search_results = $db->query("SELECT * FROM flights")->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de Vols</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background-color:rgb(116, 173, 229); color: #333; }
        .container { width: 90%; max-width: 1100px; margin: 30px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 4px 10px rgba(101, 27, 27, 0.1); }
        h1 { text-align: center; margin-bottom: 20px; color: #007bff; }
        .search-box { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; padding: 15px; background-color:rgb(143, 181, 211); border-radius: 8px; }
        select { padding: 10px; width: 200px; border: 1px solid #ccc; border-radius: 5px; }
        .btn-search { background: #007bff; color: white; border: none; padding: 10px 15px; cursor: pointer; border-radius: 5px; }
        .btn-search:hover { background: #0056b3; }
        .results { margin-top: 20px; }
        .flights-container { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
        .flight-card { background:rgb(190, 208, 225) ; padding: 15px; width: 300px; border-radius: 10px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); text-align: center; }
        .flight-card h3 { color: #007bff; }
        .flight-card p { margin: 8px 0; font-size: 14px; }
        .flight-card .price { font-size: 18px; font-weight: bold; color:rgb(110, 167, 224); }
        .buttons { margin-top: 10px; }
        .btn { display: inline-block; padding: 8px 12px; text-decoration: none; border-radius: 5px; font-size: 14px; }
        .btn-reserve { background:rgb(7, 8, 7); color: white; }
        .btn-ticket { background: #ffc107; color: white; }
        .btn-reserve:hover { background:rgb(137, 183, 232); }
        .btn-ticket:hover { background: #e0a800; }
    </style>
</head>
<body>

    <div class="container">
        <h1>Recherche de Vols</h1>

        <form method="POST" action="">
            <div class="search-box">
                <select name="class">
                    <option value="">Toutes classes</option>
                    <?php foreach ($classes as $class) : ?>
                        <option value="<?php echo htmlspecialchars($class); ?>"><?php echo htmlspecialchars($class); ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="departure">
                    <option value="">Toutes les villes de départ</option>
                    <?php foreach ($departures as $departure) : ?>
                        <option value="<?php echo htmlspecialchars($departure); ?>"><?php echo htmlspecialchars($departure); ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="arrival">
                    <option value="">Toutes les villes d'arrivée</option>
                    <?php foreach ($arrivals as $arrival) : ?>
                        <option value="<?php echo htmlspecialchars($arrival); ?>"><?php echo htmlspecialchars($arrival); ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="date">
                    <option value="">Toutes les dates disponibles</option>
                    <?php foreach ($dates as $date) : ?>
                        <option value="<?php echo htmlspecialchars($date); ?>"><?php echo htmlspecialchars($date); ?></option>
                    <?php endforeach; ?>
                </select>

                <button class="btn-search" type="submit">Rechercher</button>
            </div>
        </form>

        <div class="results">
            
            <div class="flights-container">
                <?php if (!empty($search_results)) : ?>
                    <?php foreach ($search_results as $flight) : ?>
                        <div class="flight-card">
                            <h3>Vol : <?php echo htmlspecialchars($flight['flight_code']); ?></h3>
                            <p><strong>Départ :</strong> <?php echo htmlspecialchars($flight['departure']); ?></p>
                            <p><strong>Arrivée :</strong> <?php echo htmlspecialchars($flight['arrival']); ?></p>
                            <p><strong>Date :</strong> <?php echo htmlspecialchars($flight['flight_date']); ?></p>
                            <p><strong>Heure :</strong> <?php echo htmlspecialchars($flight['flight_time']); ?></p>
                            <p class="price"><?php echo htmlspecialchars($flight['price']); ?> Milles</p>
                            <div class="buttons">
                                <a href="reserve.php?flight_id=<?php echo $flight['id']; ?>" class="btn btn-reserve">Réserver</a>
                                
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>Aucun vol disponible.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>

