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
            echo '<div id="mainpart">';
            if (!isset($_SESSION["nom"])){
                echo '<div id="nores" class="box">
                            <p>Vous n\'êtes pas connecté !</p>
                            <hr>
                            <p>Veuillez vous connecter</p>
                            <a href="../login" class="login">Se connecter </a>
                        </div>';
                goto fin;
            }
            echo '
                <div id="searchbanner" class="box">
                    <p>
                        Trouver votre chambre d\'hôtel parfaite à Night City.
                    </p>
                    <form id="searchbar" action="./" method="post">
                        <input type="text" id="name" placeholder="Nom de l\'hôtel">
                        <select id="classification">
                            <option>Qualité de l\'hôtel</option>
                            <option value="3">Luxe</option>
                            <option value="2">Moyen de gamme</option>
                            <option value="1">Bas de gamme</option>
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
                    goto fin;
                }
                $reqch1 = 'SELECT * FROM chambre WHERE id_hotelch IN $allho["id_hotel"]';
                if (isset($query["nblit"])){
                    $reqch1 .= " AND ";
                    $reqch1 .= "nb_lits >= {$query["nblit"]}";
                }
                $resch1 = $connexion->prepare($reqch1);
                $bool =  $resch1->execute();                    
                if (!$bool){
                    unset($res);
                    unset($resch1);
                    unset($connexion);
                    echo '<p class="errmsg">
                    Error during server communication </p>';
                    goto fin;                    
                }
                $allch1 = $resch1->fetchAll();
                if (count($allch1) == 0) {
                    unset($res);
                    unset($resch1);
                    unset($connexion);
                    echo '
                    <div id="nores" class="box">
                            <p>Aucun résultat à votre recherche ! </p>
                            <hr>
                            <p>Veuillez effectuer une nouvelle recherche</p>
                    </div>';
                    goto fin;
                }
                $reqre = 'SELECT * FROM reservation WHERE id_chambre IN $allch1["id_chambre"]';
                $resre = $connexion->prepare($reqre);
                $bool = $resre->execute();
                if (!$bool){
                    unset($res);
                    unset($resch1);
                    unset($resre);
                    unset($connexion);
                    echo '<p class="errmsg">
                    Error during server communication </p>';
                    goto fin;                    
                }
                $valid = array();
                $allre = $resre->fetchAll();
                foreach ($allch1 as $ch) {
                    if (!isset($query["arriver"], $query["depart"])){
                        $valid[$ch["id_chambre"]] = $ch["id_chambre"];
                    } else if (in_array($ch["id_chambre"], $allre)){
                        if ($ch["is_dortoir"]){
                            $nblit = 0;
                            foreach ($allre as $re){
                                if ($ch["id_chambre"] == $re["id_chambre"]){
                                    if (isset($query["arriver"], $query["depart"])){
                                        if (($query["arriver"] > $re["date_deb"] && $query["depart"] < $re["date_fin"]) 
                                            || ($query["arriver"] < $re["date_deb"] && $query["depart"] < $re["date_fin"] && $query["depart"] > $re["date_deb"])
                                            || ($query["arriver"] > $re["date_deb"] && $query["arriver"] < $re["date_fin"] && $query["depart"] > $re["date_fin"])
                                            || ($query["arriver"] < $re["date_deb"] && $query["depart"] > $re["date_fin"])){
                                            $nblit += $re["nb_lit"];
                                        }
                                    } else if (isset($query["arriver"])) {
                                        if ($query["arriver"] > $re["date_deb"] && $query["arriver"] < $re["date_fin"]){
                                            $nblit += $re["nb_lit"];
                                        }
                                    } else if (isset($query["depart"])){
                                        if ($query["depart"] > $re["date_deb"] && $query["depart"] < $re["date_fin"]){
                                            $nblit += $re["nb_lit"];
                                        }
                                    }
                                }
                            }
                            if ($nblit + $query["nblit"] <= $ch["nb_lits"]) {
                                $valid[$ch["id_chambre"]] = $ch["id_chambre"];
                            }                            
                        } else {
                            $trigger = 0;
                            foreach ($allre as $re){
                                if ($ch["id_chambre"] == $re["id_chambre"]){
                                    if (isset($query["arriver"], $query["depart"])){
                                        if (($query["arriver"] > $re["date_deb"] && $query["depart"] < $re["date_fin"]) 
                                            || ($query["arriver"] < $re["date_deb"] && $query["depart"] < $re["date_fin"] && $query["depart"] > $re["date_deb"])
                                            ||($query["arriver"] > $re["date_deb"] && $query["arriver"] < $re["date_fin"] && $query["depart"] > $re["date_fin"])){
                                            $trigger = 1;
                                        }
                                    } else if (isset($query["arriver"])) {
                                        if ($query["arriver"] > $re["date_deb"] && $query["arriver"] < $re["date_fin"]){
                                            $trigger = 1;
                                        }
                                    } else if (isset($query["depart"])){
                                        if ($query["depart"] > $re["date_deb"] && $query["depart"] < $re["date_fin"]){
                                            $trigger = 1;;
                                        }
                                    }
                                }
                            }
                            if ($triger == 0) {
                                $valid[$ch["id_chambre"]] = $ch["id_chambre"];
                            }
                        }
                    }
                }
                echo '<div id="findbox" class="box">';
                foreach ($valid as $ch){
                    $reqc = "SELECT * FROM hotel WHERE id_hotel = '{$ch["id_hotel"]}'";
                    $resc = $connexion->prepare($reqc);
                    $bool = $resc->execute();
                    if (!$bool){
                        unset ($resc);
                        unset($res);
                        unset($resch1);
                        unset($resre);
                        unset($connexion);
                        echo '<p class="errmsg">
                        Error during server communication </p>';
                        goto fin;                    
                    }
                    $hotel = $resc->fetch();
                    echo '<article class="res">';
                    echo '<img src="../src/imgchambre/'.$ch["img"].'" alt="Image de la chambre" class="imgch">
                            <div class="infoch">
                                <p>Hôtel : '.$hotel["nom"].'</p>
                                <p>Qualité : '.$hotel["qualiter"].'</p>
                                <p>Adresse : '.$hotel["adresse"].'</p>
                            </div>
                            <div class="infoch">
                                <p>Type de chambre : ';
                                if ($ch["is_dortoir"] == true){
                                    echo 'Dortoir';
                                } else {
                                    echo 'Chambre privative';
                                }
                                echo '</p>
                                <p>Nombre de personne : '.$ch["nb_lits"].'</p>
                                <p>Prix : '.$ch["prix"].'€</p>
                                <p>Numéro de chambre : '.$ch["nb_chambre"].'</p>
                             </div>';
                            echo '<div class="divres hidden>
                                <form class="formres" action="../reservation" method="post"
                                <input type="number" id="idch" value='.$ch["id_chambre"].' required hidden>
                                <input type="number" id="iduser" value='.$_SESSION["iduser"].' required hidden>
                                <label for="arriver">Date d\'arrivée</label>
                                <input type="date" min="2022-01-01" max="2040-01-01" id="arrivee"';
                                if (isset($query["arriver"])){
                                    echo 'value='.$query["arriver"].'';
                                }
                                echo 'required>
                                <label for="depart">Date de départ</label>
                                <input type="date" min="2022-01-01" max="2040-01-01"  id="depart"';
                                if (isset($query["depart"])){
                                    echo 'value='.$query["depart"].'';
                                }
                                echo 'required>
                                <input type="number" id="nblit" min="0"';
                                if (isset($query["nblit"])){
                                    echo 'value='.$query["nblit"].'';
                                }
                                echo 'required>
                                <button type="submit" id="searchbut">Réserver</button>';        
                    echo '</article>';
                    echo '</div>';
                }
                echo'</div>';
            unset ($resc);
            unset($res);
            unset($resch1);
            unset($resre);
            unset($connexion);
            fin:
            echo '</div>';
            ?>
            </main>
        <footer>
            <span>Cybertel - 2023</span>
        </footer>
    </body>
</html>