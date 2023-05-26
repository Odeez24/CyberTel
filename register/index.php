<?php
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
        <script src="../script/log.js"></script>
        <link href="../Style/log.css" rel="stylesheet">
        <link href="../Style/base.css" rel="stylesheet">
    </head>
    <body>
        <header>
            <a href="../index.php" id="lienlogo">
                <img id="logo" src="../src/logo.jpg" alt="Logo Cybertel"></img>
            </a>
            <a href="../index.php" id="title" class="neonText">Cybertel</a>
        </header>
        <main>
            <?php
                $err = 0;
                $firstlaunch = 0;
                $noinfo = false;
                if (isset($_POST["nom"], $_POST["prenom"], $_POST["email"], $_POST["mdp"], $_POST["tel"], $_POST["adresse"], $_POST["codepost"])){
                    if (preg_match("/^[A-Za-z^\s][A-Za-z\s]{1,14}[A-Za-z^\s]/", $_POST["nom"]) != 1){ unset($_POST["nom"]);}
                    if (preg_match("/^[A-Za-z^\s][A-Za-z\s]{1,14}[A-Za-z^\s]/", $_POST["prenom"]) != 1){ unset($_POST["prenom"]);}   if (preg_match("/^.{0,128}$/", $_POST["email"]) != 1){ unset($_POST["email"]);}
                    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){ unset($_POST["email"]);}
                    if (preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/", $_POST["mdp"]) != 1){ unset($_POST["msp"]);}
                    if (preg_match("/^((\+|00)33\s?|0)[67](\s?\d{2}){4}$/", $_POST["tel"]) != 1){unset($_POST["tel"]);}
                    if (preg_match("/^^.{0,128}$/", $_POST["adresse"]) != 1){unset($_POST["adresse"]);}
                    if (!isset($_POST["nom"], $_POST["prenom"], $_POST["email"], $_POST["mdp"], $_POST["tel"], $_POST["adresse"], $_POST["codepost"])){
                        $err = "Bad Format";
                        goto fin;
                    }
                    include "../src/mysql.php";
                    try {
                        $connexion = new PDO ('mysql:host='.MYSQL_HOST.';port=3306;dbname='.MYSQL_DB.'', MYSQL_LOG, MYSQL_PWD);
                    } catch (PDOException $e){
                        $err = "Error during server connection";
                        goto fin;
                    }
                    $req = "INSERT INTO user (nom, prenom, email, password, tel, adresse, codepost) VALUES :nom, :prenom, :email, :mdp, :tel, :adresse, :codepost";
                    $res = $connexion->prepare($req);
                    $res->bindParam(":nom", $_POST["nom"]);
                    $res->bindParam(":prenom", $_POST["prenom"]);
                    $res->bindParam(":email", $_POST["email"]);
                    $_POST["mdp"] = password_hash($_POST["mdp"], PASSWORD_DEFAULT);
                    $res->bindParam(":mdp", $_POST["mdp"]);
                    $res->bindParam(":tel", $_POST["tel"]);
                    $res->bindParam(":adresse", $_POST["adresse"]);
                    $res->bindParam(":codepost", $_POST["codepost"]);
                    $bool=  $res->execute();
                    if (!$bool){
                        unset($res);
                        unset($connexion);
                        $err = "Duplicate entry '".$_POST["email"]."' for key 'email'";
                        unset($_POST["email"]);
                        unset($_POST["mdp"]);
                        goto fin;
                    }
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
                <p>Félicitations, vous êtes maintenant inscrits.</p>
                <hr>
                <p>Connecter vous</p>
                <a class="login" href="../login">Se connecter</a>
            </div>

            <div class="boxlog"
                <?php
                    if ($firstlaunch != 0) {
                        echo "hidden";
                    }
                ?>>
                <span>S'inscrire</span>
                <form action="./" method="post" name="register">
                    <?php 
                        if ($err != 0){
                            echo "<p class=\"err errmsg\">".$err."</p>";
                        }
                    ?>
                    <label for="nom">Minimun 3 caractères, maximun 17 et pas de caractère spécial.</label>
                    <input type="text" id="nom" name="nom" placeholder="Nom" pattern="[A-Za-z^\s][A-Za-z\s]{1,14}[A-Za-z^\s]"
                    <?php 
                    if (isset($_POST["nom"])){
                        $a = $_POST["nom"];
                        echo "value = \"{$a}\"";
                    }else if (!$noinfo) {
                        echo "class=\"err\"";
                    }
                    ?>required>
                    <label for="prenom">Minimun 3 caractères, maximun 17 et pas de caractère spécial.</label>
                    <input type="text" id="prenom" name="prenom" placeholder="Prenom" pattern="[A-Za-z^\s][A-Za-z\s]{1,14}[A-Za-z^\s]"
                    <?php 
                    if (isset($_POST["prenom"])){
                        $a = $_POST["prenom"];
                        echo "value = \"{$a}\"";
                    }else if (!$noinfo) {
                        echo "class=\"err\"";
                    }
                    ?> required>
                    <input type="email" id="email" name="email" placeholder="****@****.com" 
                    <?php 
                    if (isset($_POST["email"])){
                        $a = $_POST["email"];
                        echo "value = \"{$a}\"";
                    }else if (!$noinfo) {
                        echo "class=\"err\"";
                    }
                    ?>required>
                    <label>Minimum 8 caractères, au moins une majuscule, une minuscule et une lettre</label>
                    <input type="password" id="mdp" name="mdp"placeholder="password" pattern="(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$"
                    <?php 
                    if (isset($_POST["mdp"])){
                        $a = $_POST["mdp"];
                        echo "value = \"{$a}\"";
                    }else if (!$noinfo) {
                        echo "class=\"err\"";
                    }
                    ?>required>
                    <img src="../src/closeeyes.jpg" id="regeyes" class="eyes" onclick="viewmdpreg()">
                    <input type="text" id="adresse" name="adresse" placeholder="x rue xxx Ville" pattern="^.{0,128}$"
                    <?php 
                    if (isset($_POST["adresse"])){
                        $a = $_POST["adresse"];
                        echo "value = \"{$a}\"";
                    }else if (!$noinfo) {
                        echo "class=\"err\"";
                    }
                    ?>required>
                    <input type="number" id="codepost" name="codepost" placeholder="xxxxx" min="00000"
                    <?php 
                    if (isset($_POST["codepost"])){
                        $a = $_POST["codepost"];
                        echo "value = \"{$a}\"";
                    }else if (!$noinfo) {
                        echo "class=\"err\"";
                    }
                    ?>required>
                    <input type="tel" id="tel" name="tel" placeholder="(0x ou +33)" pattern="((\+|00)33\s?|0)[67](\s?\d{2}){4}$"
                    <?php 
                    if (isset($_POST["tel"])){
                        $a = $_POST["tel"];
                        echo "value = \"{$a}\"";
                    }else if (!$noinfo) {
                        echo "class=\"err\"";
                    }
                    ?>required>
                    <button type="submit">S'inscrire</button>
                </form>
            <hr>
            <p>Vous avez déjà un compte ?</p>
            <a class="login" href="../login">Se connecter</a>
            </div>
        </main>
        <footer>
            <span>Cybertel - 2023</span>
        </footer>
    </body>
</html>