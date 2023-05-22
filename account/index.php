<?php
    session_start();
    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
?>

<!DOCTYPE <!DOCTYPE html5>
<html lang="fr">
    <head>
        <title>Cybertel</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="../script/jquery-3.7.0.min.js"></script>
        <script src="../script/account.js"></script>
        <link href="../Style/account.css" rel="stylesheet">
        <link href="../Style/base.css" rel="stylesheet">
    </head>
    <body>
        <header>
            <a href="../index.php" id="lienlogo">
                <img id="logo" src="../src/logo.jpg" alt="Logo Cybertel"></img>
            </a>
            <a href="../index.php" id="title" class="neonText">Cybertel</a>
            <?php
                if (isset($_SESSION["nom"])) {
                    echo '<a id="account" class="log" href="./account">'.$_SESSION["nom"].' '.$_SESSION["prenom"].'</a>';
                }
            ?>
        </header>
        <main>
            <div id="notconnect" 
                <?php
                    if (isset($_SESSION["nom"])) {
                        echo "hidden";
                    }
                ?>>
                <p>Vous n'êtes pas connecter !</p>
                <hr>
                <p>Vous avez déjà un compte ?</p>
                <a class="login" href="../login">Se connecter</a>
                <p>Vous n'avez pas de compte ?</p>
                <a class="login" href="../register">S'inscrire</a>
            </div>
            <div id="infoperso" class="box" 
                <?php
                    if (!isset($_SESSION["nom"])) {
                        echo "hidden";
                    }
                ?>>
                <div id="surinfo">
                    <p>Information Personnel</p>
                    <div id="info">
                        <p>Nom : <?php echo $_SESSION["nom"]?></p>
                        <p>Prenom : <?php echo $_SESSION["prenom"]?></p>
                        <p>Email : <?php echo $_SESSION["email"]?></p>
                        <p>Téléphone : <?php echo $_SESSION["tel"]?></p>
                    </div>
                    <p>Changer de mot de passe</p>
                    <div id="changemdp">
                        <form action="./" method="post">
                            <label for="existmdp">Ancien mot de passe</label>
                            <input type="password" id="existmdp"name="existmdp">
                            <img src="../src/closeeyes.jpg" id="existeyes" class="eyes" onclick="viewmdpex()">
                            <label for="newmdp">Nouveaux mot de passe</label>
                            <input type="password" id="newmdp"name="newmdp">
                            <img src="../src/closeeyes.jpg" id="neweyes" class="eyes" onclick="viewmdpnew()">
                            <label for="newmdp2">Confirmation mot de passe</label>
                            <input type="password" id="newmdp2"name="newmdp2">
                            <img src="../src/closeeyes.jpg" id="neweyes2" class="eyes" onclick="viewmdpnew2()">
                        </form>
                    </div>
                </div>
            </div>
            <div id="mainpart" class="box"
                <?php
                    if (!isset($_SESSION["nom"])) {
                        echo "hidden";
                    }
                ?>>
            </div>
        </main>
        <footer>
            <span>Cybertel - 2023</span>
        </footer>
    </body>
</html>