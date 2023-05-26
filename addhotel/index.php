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
                if (isset($_POST["nom"], $_POST["adresse"], $_POST["class"], $_POST["note"])){
                    include "../src/mysql.php";
                    try {
                        $connexion = new PDO ('mysql:host='.MYSQL_HOST.';port=3306;dbname='.MYSQL_DB.'', MYSQL_LOG, MYSQL_PWD);
                    } catch (PDOException $e){
                        $err = "Error during server connection";
                        goto fin;
                    }
                    $req = "INSERT INTO hotel (nom, adresse, qualite, etoile) VALUES (?, ?, ?, ?)";
                    $res = $connexion->prepare($req);
                    $bool=  $res->execute([$_POST["nom"], $_POST["adresse"], $_POST["class"], $_POST["note"]]);
                    if (!$bool){
                        unset($res);
                        unset($connexion);
                        echo '<p class="errmsg">
                        Error during server communication </p>';
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
                <p>Hôtel ajouter.</p>
                <hr>
                <p>Ajouter un hôtel</p>
                <a class="login" href="./">Ajouter un hôtel</a>
            </div>

            <div class="boxadd"
                <?php
                    if ($firstlaunch != 0) {
                        echo "hidden";
                    }
                ?>>
                <span>Ajouter un hotel</span>
                <form action="./" method="post" name="addho">
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
                    <input type="text" id="adresse" name="adresse" placeholder="adresse de l'hôtel"
                    <?php 
                    if (isset($_POST["adresse"])){
                        $a = $_POST["adresse"];
                        echo "value = \"{$a}\"";
                    }else if (!$noinfo) {
                        echo "class=\"err\"";
                    }
                    ?> required>
                    <select id="class" name="class"<?php 
                    if (isset($_POST["class"])){
                        $a = $_POST["class"];
                        echo "value = \"{$a}\"";
                    }else if (!$noinfo) {
                        echo "class=\"err\"";
                    }
                    ?>>
                            <option>Qualité de l'hôtel</option>
                            <option value="3">Luxe</option>
                            <option value="2">Moyen</option>
                            <option value="1">Bas</option>
                    </select>
                    <input type="" id="note" placeholder="note" min="0" max="5" name="note"
                    <?php 
                    if (isset($_POST["note"])){
                        $a = $_POST["note"];
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