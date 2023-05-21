<!DOCTYPE <!DOCTYPE html5>
<html lang="fr">
    <head>
        <title>Cybertel</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="../script/jquery-3.7.0.min.js"></script>
        <script src="../script/log.js"></script>
        <link href="../Style/log.css" rel="stylesheet">
    </head>
    <body>
        <header>
            <a href="../index.php" id="lienlogo">
                <img id="logo" src="../src/logo.jpg" alt="Logo Cybertel"></img>
            </a>
            <a href="../index.php" id="title">Cybertel</a>
        </header>
        <main>
            <?php
                $err = 0;
                $firstlaunch = 0;
                $noinfo = false;
                if (isset($_POST["email"], $_POST["mdp"])){
                    include "../src/mysql.php";
                    try {
                        $connexion = new PDO (DSN, MYSQL_LOG, MYSQL_PWD);
                    } catch (PDOException $e){
                        $err = "Error during server connection";
                        goto fin;
                    }
                    $req = "SELECT * FROM account WHERE email = '{$_POST["email"]}';";
                    $res = $connexion->prepare($req);
                    if (!$res->execute()){
                        unset($res);
                        unset($connexion);
                        $err = "Error during server communication";
                        goto fin;
                    }
                    if (count($res->fetch()) == 0){
                        unset($res);
                        unset($connexion);
                        $err = "Mauvais email";
                        goto fin;
                    }
                    $account = $res->fetch();
                    if (!password_verify($account["password"], $_POST["mdp"])){
                        unset($res);
                        unset($connexion);
                        $err = "Mauvais mot de passe";
                        goto fin;
                    }

                    session_start();

                    $_SESSION ["nom"] = $account["nom"];
                    $_SESSION ["prenom"] = $account["prenom"];
                    $_SESSION ["email"] = $account["email"];
                    $_SESSION ["tel"] = $account["tel"];
                    unset($res);
                    unset($connexion);
                    $log = session_status();
                    echo $log;
                    echo "test22";
                    $firstlaunch = 1;
                    fin:
                } else {
                    $noinfo = true;
                }
            ?>
            <div class="validate"
                <?php
                if ($firstlaunch == 0){
                    echo "hidden";
                }
                ?>>
                <p>Félicitations, vous êtes maintenant connecter.</p>
                <hr>
                <p>Votre compte</p>
                <a class="login" href="../account">Votre compte</a>
            </div>
            <div class="boxlog">
                <span>Se connecter</span>
                <form name="login" action="./" method="post">
                    <label for="email">Adresse mail</label>
                    <input type="email" name="email"
                    <?php 
                    if (isset($_POST["email"])){
                        $a = $_POST["email"];
                        echo "value = \"{$a}\"";
                    }else if (!$noinfo) {
                        echo "class=\"err\"";
                    }
                    ?> required>
                    <label for="mdp">Mot de passe</label>
                    <input type="password" name="mdp" id="mdp"
                    <?php 
                    if (isset($_POST["mdp"])){
                        $a = $_POST["mdp"];
                        echo "value = \"{$a}\"";
                    }else if (!$noinfo) {
                        echo "class=\"err\"";
                    }
                    ?> required>
                    <img src="../src/closeeyes.jpg" id="logeyes" class="eyes" onclick="viewmdp()">
                    <button type="submit">Se connecter</button>
                </form>
                <hr>
                <p>Vous n'avez pas de compte ?</p>
                <a class="login" href="../register">S'inscrire</a>
            </div>
        </main>
        <footer>
            <span>Cybertel - 2023</span>
        </footer>
    </body>
</html>