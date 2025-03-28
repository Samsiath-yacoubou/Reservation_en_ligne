<?php
session_start();
include('../db.php');


if (isset($_GET['flight_id'])) {
    $_SESSION['reserved_flight_id'] = (int)$_GET['flight_id'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $db->prepare("INSERT INTO users (nom, prenom, email, password) VALUES (?, ?, ?, ?)");

        if ($stmt->execute([$nom, $prenom, $email, $password])) {
            
            $_SESSION['user_id'] = $db->lastInsertId();
            $_SESSION['nom'] = $nom;

            // Redirection après inscription
            if (isset($_SESSION['reserved_flight_id'])) {
                header("Location: connexion.php?flight_id=" . $_SESSION['reserved_flight_id']);
                unset($_SESSION['reserved_flight_id']);
            } else {
                header("Location: profil.php");
            }
            exit();
        }
    } catch (PDOException $e) {
        $error = "Erreur: L'email existe déjà";
    }
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Reserver</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            min-height: 1OOvh;
            background-color: rgb(182, 201, 211);
        }

        .login-box {
            display: flex;
            justify-content: center;
            flex-direction: column;
            width: 440px;
            height: 480px;
            padding: 30px;
        }

        .login-header {
            text-align: center;
            margin: 2Opc 0 40px O;
            margin-bottom: 10px;
        }

        .login-header header {
            color: #333;
            font-size: 30px;
            font-weight: 600;
        }

        .input-box .input-field {
            width: 100%;
            height: 60px;
            font-size: 17px;
            padding: 0 25px 0 25px;
            margin-bottom: 15px;
            border-radius: 30px;
            border: none;
            box-shadow: 0px 5px 10px 1px rgba(0, 0, 0, 0.05);
            outline: none;
            transition: .3s;
        }

        ::placeholder {
            font-weight: 500;
            color: #171414;
        }

        .forgot {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }

        section {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #555;
        }

        #check {
            margin-right: 10px;

        }

        a {
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .input-submit {
            position: relative;
        }

        .submit-btn {
            width: 100%;
            height: 60px;
            background: #222;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: .3s;
        }

        .input-submit label {
            position: absolute;
            top: 45%;
            left: 50%;
            color: #fff;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            cursor: pointer;
        }

        .submit-btn:hover {
            background: #000;
            transform: scale(1.05, 1);
        }

        .sign-up-link {
            text-align: center;
            font-size: 15px;
            margin-top: 20px;
        }

        .sign-up-link a {
            color: #000;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <form action="" method="post">
        <div class="login-box">
            <div class="login-header">
                <header>Inscription</header>
            </div>
            <div class="input-box">
                <input type="text" class="input-field" placeholder="Nom" name="nom" autocomplete="off" required>
            </div>
            <div class="input-box">
                <input type="text" class="input-field" placeholder="Prenom" name="prenom" autocomplete="off" required>
            </div>
            <div class="input-box">
                <input type="text" class="input-field" placeholder="Email" name="email" autocomplete="off" required>
            </div>
            <div class="input-box">
                <input type="password" class="input-field" placeholder="Mot de passe" name="password" autocomplete="off" required>
            </div>

            <div class="input-submit">
                <button class="submit-btn" id="submit"></button>
                <label for="submit">S'inscrire</label>
            </div>
            <!-- Ajoutez ceci dans votre formulaire d'inscription -->
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    </form>
</body>

</html>