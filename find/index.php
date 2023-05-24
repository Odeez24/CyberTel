<?php
    session_start();
?>

<!DOCTYPE <!DOCTYPE html5>
<html lang="fr">
    <head>
        <title>Cybertel</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../Style/find.css" rel="stylesheet">
        <link href="../Style/base.css" rel="stylesheet">
    </head>
    <body>
        <header>
            <a href="../index.php" id="lienlogo">
                <img id="logo" src="../src/logo.jpg" alt="Logo Cybertel"></img>
            </a>
            <a href="../index.php" id="title" class="neonText">Cybertel</a>
            <?php
                if (!isset($_SESSION["nom"])){
                    echo '<a id="login" class="log" href="../login">Se connecter</a>';
                    echo '<a id="register" class="log" href="../register">S\'inscrire</a>';
                }else {
                    echo '<a id="account" class="log" href="../account">'.$_SESSION["nom"].' '.$_SESSION["prenom"].'</a>';
                }
            ?>
        </header>
        <main>
            <?php
            $err = 0;
            echo '
            <div id="mainpart">
                <div id="searchbanner" class="box">
                    <p>
                        Trouver votre chambre d\'hôtel parfait à Night City.
                    </p>
                    <form id="searchbar" action="./" method="post">
                        <input type="text" id="name" placeholder="Nom de l\'hôtel">
                        <select id="classification">
                            <option>Qualité de l\'hôtel</option>
                            <option value="3">Luxe</option>
                            <option value="2">Moyen</option>
                            <option value="1">Bas</option>
                        </select>
                        <label for="arriver">Date d\'arrivée</label>
                        <input type="date" min="2022-01-01" max="2040-01-01"  id="arrivee">
                        <label for="depart">Date de départ</label>
                        <input type="date" min="2022-01-01" max="2040-01-01"  id="depart">
                        <input type="number" id="nblit" min="0" placeholder="Lits">
                        <button type="submit" id="searchbut">Rechercher</button>
                    </form>
                    <form>
                    </form>
                </div>
            </div>';
            include "../src/mysql.php";
                try {
                    $connexion = new PDO ('mysql:host='.MYSQL_HOST.';port=3306;dbname='.MYSQL_DB.'', MYSQL_LOG, MYSQL_PWD);
                } catch (PDOException $e){
                     echo '<p class="errmsg">
                    Error during server communication </p>';                       
                goto fin;
                }
                $query = array();
                if (isset($_POST["name"]) && $_POST["name"] != null) {
                    if (preg_match("/[\S^\s][\S\s]{0,63}[\S^\s]/", $_POST["name"]) != 1){
                        unset($_POST["name"]);
                        echo '<p class="errmsg">
                        Bad format for nom de l\'hôtel
                        </p>';  
                        goto fin;
                    }
                    $query["name"] = $_POST["name"];
                }
                $query = array();
                if (isset($_POST["classification"]) && $_POST["classification"] != null) {
                    if (1 > $_POST["classification"] || $_POST["classification"] > 3) {
                        unset($_POST["classification"]);
                        echo '<p class="errmsg">
                        Bad format for Qualité de l\'hôtel
                        </p>';  
                        goto fin;
                    }
                    $query["class"] = $_POST["classification"];
                }
                if (isset($_POST["arriver"]) && $_POST["arriver"] != null) {
                    if (2022-01-01 > $_POST["arriver"] || $_POST["arriver"] > 2040-01-01) {
                        unset($_POST["arriver"]);
                        echo '<p class="errmsg">
                        Bad format for date d\'arriver
                        </p>';  
                        goto fin;
                    }
                    $query["arriver"] = $_POST["arriver"];
                }
                if (isset($_POST["depart"]) && $_POST["depart"] != null) {
                    if (2022-01-01 > $_POST["depart"] || $_POST["depart"] > 2040-01-01) {
                        unset($_POST["depart"]);
                        echo '<p class="errmsg">
                        Bad format for date de départ
                        </p>';  
                        goto fin;
                    }
                    $query["depart"] = $_POST["depart"];
                }
                if (isset($_POST["nblit"]) && $_POST["nblit"] != null) {
                    if (0 > $_POST["nblit"]) {
                        unset($_POST["nblit"]);
                        echo '<p class="errmsg">
                        Bad format for nombre de lits
                        </p>';  
                        goto fin;
                    }
                    $query["nblit"] = $_POST["nblit"];
                }
                $request = "SELECT * FROM hotel";
                if (count($query) > 0){
                    $request .= " WHERE ";
                    $first = true;
                    foreach($query as $key => $value){
                        if (!$first){
                            $request .= " AND ";
                        }
                        if ($key == "nom"){
                            $request .= "name LIKE {$_POST["name"]}";
                        } else if ($key == "class" ){
                            $request .= "qualite = {$value}";
                        }
                        $first = false;
                    }
                }
                $res = $connexion->prepare($request);
                $bool =  $res->execute();                    
                if (!$bool){
                    unset($res);
                    unset($connexion);
                    echo '<p class="errmsg">
                    Error during server communication </p>';
                    goto fin;                    
                }
                $allho = $res->fetchAll();
                if (count($allho) == 0) {
                    unset($res);
                    unset($connexion);
                    echo '
                    <div id="nores" class="box">
                            <p>Aucun résultat à votre recherche ! </p>
                            <hr>
                            <p>Veuillez effectuer une nouvelle recherche</p>
                        </div>';
                }
                
            fin:
            ?>
            </main>
        <footer>
            <span>Cybertel - 2023</span>
        </footer>
    </body>
</html>