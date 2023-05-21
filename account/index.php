<?php
session_start();
$log = session_status();
?>

<!DOCTYPE <!DOCTYPE html5>
<html lang="fr">
    <head>
        <title>Cybertel</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../Style/account.css" rel="stylesheet">
    </head>
    <body>
        <header>
            <a href="../index.php" id="lienlogo">
                <img id="logo" src="../src/logo.jpg" alt="Logo Cybertel"></img>
            </a>
            <a href="../index.php" id="title">Cybertel</a>
            <?php
                echo '<a id="account" class="log" href="./account">'.$_SESSION["nom"]  .$_SESSION["prenom"].'</a>';
            ?>
        </header>
        <main>
            
        </main>
        <footer>
            <span>Cybertel - 2023</span>
        </footer>
    </body>
</html>