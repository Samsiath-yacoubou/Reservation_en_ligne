<?php
include('../db.php');

$email = "admin@example.com";  // Remplace par ton email
$password = "admin23";  // Remplace par ton mot de passe
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

$stmt = $db->prepare("INSERT INTO admins (email, password) VALUES (?, ?)");
$stmt->execute([$email, $hashed_password]);

echo "Nouvel admin ajouté avec succès.";
?>
