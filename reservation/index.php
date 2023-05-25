<?php
    session_start();
?>

<!DOCTYPE <!DOCTYPE html5>
<html lang="fr">
    <head>
        <title>Cybertel</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="./Style/main.css" rel="stylesheet">
        <link href="./Style/base.css" rel="stylesheet">
    </head>
    <body>
        <header>
            <a href="index.php" id="lienlogo">
                <img id="logo" src="./src/logo.jpg" alt="Logo Cybertel"></img>
            </a>
            <a href="index.php" id="title" class="neonText">Cybertel</a>
            <?php
                if (!isset($_SESSION["nom"])){
                    echo '<a id="login" class="log" href="./login">Se connecter</a>';
                    echo '<a id="register" class="log" href="./register">S\'inscrire</a>';
                }else {
                    echo '<a id="account" class="log" href="./account">'.$_SESSION["nom"].' '.$_SESSION["prenom"].'</a>';
                }
            ?>
        </header>
        <main>
            <?php
            if (isset($_POST["idch"], $_POST["iduser"], $_POST["arrivee"], $_POST["depart"], $_POST["nblit"])){
                if (!isset($_POST["nom"], $_POST["prenom"], $_POST["email"], $_POST["mdp"], $_POST["tel"], $_POST["adresse"], $_POST["codepost"])){
                    echo '<p class="errmsg">
                    bad format </p>';
                    goto fin;
                }
            }

            fin:
            ?>
        </main>
        <footer>
            <span>Cybertel - 2023</span>
        </footer>
    </body>
</html>