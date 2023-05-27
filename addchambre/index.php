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
        <link href="../Style/add.css" rel="stylesheet">
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
                if (isset($_POST["nom"], $_POST["prix"], $_POST["nblit"], $_POST["img"], $_POST["num"])){
                    if (!isset($_POST["isdortoir"])){
                        $isdortoir = 0;
                    } else {
                        $isdortoir = 1;
                    }
                    include "../src/mysql.php";
                    try {
                        $connexion = new PDO ('mysql:host='.MYSQL_HOST.';port=3306;dbname='.MYSQL_DB, MYSQL_LOG, MYSQL_PWD);
                    } catch (PDOException $e){
                        $err = "Error during server connection";
                        goto fin;
                    }
                    $req = "SELECT id_hotel FROM hotel WHERE nom = :idhot;";
                    $res = $connexion->prepare($req);
                    $res->bindParam(':idhot', $_POST["nom"]);
                    $bool = $res->execute();
                    if (!$bool){
                        unset($res);
                        unset($connexion);
                        echo '<p class="errmsg">
                        Error during server communication </p>';
                        goto fin;
                    }
                    $id = $res->fetch();
                    $id = $id[0];
                    $reqa = "INSERT INTO chambre (id_hotelch, is_dortoir, prix, nb_lits, img, nb_chambre) VALUES (?, ?, ?, ?, ?, ?)";
                    $resa = $connexion->prepare($reqa);
                    $bool=  $resa->execute([$id, $isdortoir, $_POST["prix"], $_POST["nblit"], $_POST["img"], $_POST["num"]]);
                    if (!$bool){
                        unset($res);
                        unset($resa);
                        unset($connexion);
                        echo '<p class="errmsg">
                        Error during server communication </p>';
                        goto fin;
                    }
                    $firstlaunch = 1;
                    unset($res);
                    unset($resa);
                    unset($connexion);

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
                <p>Chambre ajouter.</p>
                <hr>
                <p>Ajouter une chambre</p>
                <a class="login" href="./">Ajouter une chambre</a>
            </div>

            <div class="boxadd"
                <?php
                    if ($firstlaunch != 0) {
                        echo "hidden";
                    }
                ?>>
                <span>Ajouter une chambre</span>
                <form action="./" method="post" name="addch">
                    <?php 
                        if ($err != 0){
                            echo "<p class=\"err errmsg\">".$err."</p>";
                        }
                    ?>
                    <input type="text" id="nom" name="nom" placeholder="Nom de l'hotel"
                    <?php 
                    if (isset($_POST["nom"])){
                        $a = $_POST["nom"];
                        echo "value = \"{$a}\"";
                    }else if (!$noinfo) {
                        echo "class=\"err\"";
                    }
                    ?>required>
                    <label for="isdortoir">Est un dortoir</label>
                    <input type="checkbox" id="isdortoir" name="isdortoir">
                    <input type="number" id="prix" name="prix"placeholder="prix"
                    <?php 
                    if (isset($_POST["prix"])){
                        $a = $_POST["prix"];
                        echo "value = \"{$a}\"";
                    }else if (!$noinfo) {
                        echo "class=\"err\"";
                    }
                    ?>required>
                    <input type="number" id="num" name="num"placeholder="numéro chambre"
                    <?php 
                    if (isset($_POST["num"])){
                        $a = $_POST["num"];
                        echo "value = \"{$a}\"";
                    }else if (!$noinfo) {
                        echo "class=\"err\"";
                    }
                    ?>required>
                    <input type="number" id="nblit" name="nblit"placeholder="nb lit"
                    <?php 
                    if (isset($_POST["nblit"])){
                        $a = $_POST["nblit"];
                        echo "value = \"{$a}\"";
                    }else if (!$noinfo) {
                        echo "class=\"err\"";
                    }
                    ?>required>
                    <input type="text" id="img" name="img"placeholder="nom de l'image"
                    <?php 
                    if (isset($_POST["img"])){
                        $a = $_POST["img"];
                        echo "value = \"{$a}\"";
                    }else if (!$noinfo) {
                        echo "class=\"err\"";
                    }
                    ?>required>
                    <button type="submit">Ajouter</button>
                </form>
            </div>
        </main>
        <footer>
            <span>Cybertel - 2023</span>
        </footer>
    </body>
</html>