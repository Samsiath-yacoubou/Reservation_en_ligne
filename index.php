<?php
session_start();
include('db.php'); 
if (isset($_GET['flight_id'])) {
  $_SESSION['reserved_flight_id'] = $_GET['flight_id']; 
}


$stmt = $db->query("SELECT * FROM flights ORDER BY id DESC LIMIT 4");
$flights = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/styles.css" />
  <title>Air-Bénin</title>
</head>
<body>

  <header class="section__container">
    <nav>
      <div class="nav__logo">Air-Bénin</div>
      <button class="btn">
        <a href="../Reservation_en_ligne/pages/connexion.php">Réserver</a>
      </button>
    </nav>
    <h1 class="section__header">Montez à bord<br />Pour une expérience magique</h1>
    <img src="assets/header.jpg" alt="header" />
  </header>

  <h1 style="text-align: center;">🛫 Derniers Vols Disponibles</h1>

  <div class="flights-container">
    <?php foreach ($flights as $flight) : ?>
      <div class="flight-card">
        <h3><?= htmlspecialchars($flight['flight_code']) ?></h3>
        <p><strong>Départ :</strong> <?= htmlspecialchars($flight['departure']) ?></p>
        <p><strong>Arrivée :</strong> <?= htmlspecialchars($flight['arrival']) ?></p>
        <p><strong>Heure :</strong> <?= htmlspecialchars($flight['flight_time']) ?></p>
        <p class="price"><?= htmlspecialchars($flight['price']) ?> Milles</p>

        <!-- Lien de réservation -->
        <?php if (isset($_SESSION['user_id'])) : ?>
          <a href="../Reservation_en_ligne/pages/reserve.php?flight_id=<?= $flight['id'] ?>" class="book-btn">Réserver</a>
        <?php else : ?>
          <a href="../Reservation_en_ligne/pages/connexion.php?flight_id=<?= $flight['id'] ?>" class="book-btn">Réserver</a>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>

  <footer class="footer">
    <div class="section__container footer__container">
      <div class="footer__col">
        <h3>Air-Bénin</h3>
        <p>
          Avec un engagement fort envers la satisfaction de ses clients et <br>
          une passion pour les voyages aériens, Air Bénin offre un service <br>
          exceptionnel et des voyages sans faille. Chaque vol incarne notre <br>
          dévouement à offrir une expérience unique, alliant confort, sécurité et efficacité. <br>
          Nous mettons un point d'honneur à faire de chaque
          voyage une aventure agréable et mémorable.
        </p>
      </div>

      <div class="footer__col">
        <h4>CONTACT</h4>
        <p>Email: airbenin@gmail.com</p>
      </div>
    </div>
    <div class="section__container footer__bar">
      <p>Copyright © 2025 - Tous droits réservés.</p>
      <div class="socials">
        <span><i class="ri-facebook-fill"></i></span>
        <span><i class="ri-twitter-fill"></i></span>
        <span><i class="ri-instagram-line"></i></span>
        <span><i class="ri-youtube-fill"></i></span>
      </div>
    </div>
  </footer>

</body>
</html>
