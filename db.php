<?php

$db_name = "reservation"; 
$db_user = "root";
$db_password = "";

try {
    
    $db = new PDO("mysql:host=127.0.0.1;dbname=" . $db_name . ";charset=utf8", $db_user, $db_password);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $th) {
    echo "Erreur de connexion : " . $th->getMessage();
}

const APP_NAME = "reservation";
?>