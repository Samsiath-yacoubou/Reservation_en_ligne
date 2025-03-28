<?php

$db_name = "if0_38625485_airbenin"; 
$db_user = "if0_38625485";
$db_password = "wONB3RDswlY";

try {
    
    $db = new PDO("mysql:host=sql310.infinityfree.com;dbname=" . $db_name . ";charset=utf8", $db_user, $db_password);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $th) {
    echo "Erreur de connexion : " . $th->getMessage();
}

const APP_NAME = "reservation
";
?>