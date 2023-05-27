<?php
    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    function affetoile($nb) {
        echo "<div class='star'>";
        if ($nb > 5) {
            $nb = 5;
        }
        for ($k = 0; $k < $nb; $k += 1) {
            echo "<img src='../src/etoileplein.png' class='note' alt='full'/>";
        }
        for ($k = 5; $k > $nb; $k -= 1) {
            echo "<img src='../src/etoilevide.png' class='note' alt='empty'/>";
        }
        echo "</div>";
    }
?>

<!DOCTYPE html5>
<html lang="fr">
    <head>
        <title>Cybertel</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../Style/find.css" rel="stylesheet">
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
            <div id="mainpart">
            <?php
            $err = 0;
            if (!isset($_SESSION["nom"])){
                echo '<div id="nores" class="box">
                            <p>Vous n\'êtes pas connecté !</p>
                            <hr>
                            <p>Veuillez vous connecter</p>
                            <a href="../login" class="login">Se connecter </a>
                        </div>';
                goto fin;
            }
            ?>
            <div id="banner">
                <div id="searchbanner" class="box">
                    <p>
                        Trouver votre chambre d'hôtel parfaite à Night City.
                    </p>
                    <form id="searchbar" action="./" method="post">
                        <input type="text" id="name" name="name" placeholder="Nom de l'hôtel"
                        <?php
                        if (isset($_POST["name"])) {
                            echo 'value="'.$_POST["name"].'"';
                        }
                        ?>>
                        <select id="class" name="class"
                        <?php
                        if (isset($_POST["class"])) {
                            echo 'value="'.$_POST["class"].'"';
                        }
                        ?>>
                            <option value="-1">Qualité de l'hôtel</option>
                            <option value="3">Luxe</option>
                            <option value="2">Moyen de gamme</option>
                            <option value="1">Bas de gamme</option>
                        </select>
                        <label for="arriver">Date d'arrivée</label>
                        <input type="date" min="2022-01-01" max="2040-01-01" name="arriver" id="arriver"
                        <?php
                        if (isset($_POST["arriver"])) {
                            echo 'value="'.$_POST["arriver"].'"';
                        }
                        ?>>
                        <label for="depart">Date de départ</label>
                        <input type="date" min="2022-01-01" max="2040-01-01"  id="depart" name="depart"
                        <?php
                        if (isset($_POST["depart"])) {
                            echo 'value="'.$_POST["depart"].'"';
                        }
                        ?>>
                        <input type="number" id="nblit" min="0" placeholder="Lits" name="nblit"
                        <?php
                        if (isset($_POST["nblit"])) {
                            echo 'value="'.$_POST["nblit"].'"';
                        }
                        ?>>
                        <button type="submit" id="searchbut">Rechercher</button>
                    </form>
                    <form>
                    </form>
                </div>
            </div>
            <?php
            $err = 0;
            include "../src/mysql.php";
                try {
                    $connexion = new PDO ('mysql:host='.MYSQL_HOST.';port=3306;dbname='.MYSQL_DB, MYSQL_LOG, MYSQL_PWD);
                } catch (PDOException $e){
                    $err = 'Error during server communication';                       
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
                if (isset($_POST["class"]) && $_POST["class"] != null) {
                    if ($_POST["class"] == -1) {
                        unset($_POST["class"]);
                    } else if (1 > $_POST["class"] || $_POST["class"] > 3) {
                        unset($_POST["class"]);
                        echo '<p class="errmsg">
                        Bad format for Qualité de l\'hôtel
                        </p>';  
                        goto fin;
                    } else {
                        $query["class"] = $_POST["class"];
                    }
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
                $request = "SELECT id_hotel FROM hotel";
                if (isset($query["name"]) || isset($query["class"])){
                    echo "test";
                    $request .= " WHERE ";
                    $first = true;
                    foreach($query as $key => $value){
                        if ($key == "name"){
                            if (!$first){
                                $request .= " AND ";
                            }
                            $request .= "nom LIKE :name";
                        } else if ($key == "class" ){
                            if (!$first){
                                $request .= " AND ";
                            }
                            $request .= "qualite = :class";
                        }
                        $first = false;
                    }
                }
                $request .= ";";
                $res = $connexion->prepare($request);
                if (isset($query["name"])){
                    $query["name"] = strtolower($query["name"]);
                    $query["name"] = str_replace(" ", "", $query["name"]);
                    $res->bindParam(':name', $query["name"]);
                }
                if (isset($query["class"])){
                    $res->bindParam(':class', $query["class"]);
                }
                $bool =  $res->execute();                    
                if (!$bool){
                    unset($res);
                    unset($connexion);
                    $err = 'Error during server communication';
                    goto fin;                    
                }
                $allho1 = $res->fetchAll();
                if (count($allho1) == 0) {
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
                $allho = array();
                foreach($allho1 as $key => $value){
                    $allho[] = $value["id_hotel"];
                }
                $reqch1 = 'SELECT * FROM chambre WHERE id_hotelch IN ('.implode (',' ,$allho).')';
                if (isset($query["nblit"])){
                    $reqch1 .= " AND ";
                    $reqch1 .= "nb_lits >= :nblit";
                }
                $reqch1 .= ';';
                $resch1 = $connexion->prepare($reqch1);
                if (isset($query["nblit"])){
                    $resch1->bindParam(':nblit', $query["nblit"]);
                }
                $bool = $resch1->execute();                    
                if (!$bool){
                    unset($res);
                    unset($resch1);
                    unset($connexion);
                    $err = 'Error during server communication';
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
                $allid = array();
                foreach ($allch1 as $ch) {
                    $allid[] = $ch["id_chambre"];
                }
                $reqre = 'SELECT * FROM reservation WHERE id_chambre IN ('.implode (',', $allid).')';
                $resre = $connexion->prepare($reqre);
                $bool = $resre->execute();
                if (!$bool){
                    unset($res);
                    unset($resch1);
                    unset($resre);
                    unset($connexion);
                    $err = 'Error during server communication';
                    goto fin;                    
                }
                $valid = array();
                $allre = $resre->fetchAll();
                if (count($allre) == 0){
                    foreach ($allch1 as $ch) {
                        $valid[$ch["id_chambre"]] = $ch["id_chambre"];
                    }
                }
                foreach ($allch1 as $ch) {
                    if (!isset($query["arriver"], $query["depart"])){
                        $valid[$ch["id_chambre"]] = $ch["id_chambre"];
                    } else {
                        if ($ch["is_dortoir"]){
                            $nblit = 0;
                            foreach ($allre as $re){
                                    if ($ch["id_chambre"] == $re["id_chambre"]){
                                        if (isset($query["arriver"], $query["depart"])){
                                            if (($query["arriver"] >= $re["date_deb"] && $query["depart"] <= $re["date_fin"]) 
                                                || ($query["arriver"] <= $re["date_deb"] && $query["depart"] <= $re["date_fin"] && $query["depart"] >= $re["date_deb"])
                                                || ($query["arriver"] >= $re["date_deb"] && $query["arriver"] <= $re["date_fin"] && $query["depart"] >= $re["date_fin"])
                                                || ($query["arriver"] <= $re["date_deb"] && $query["depart"] >= $re["date_fin"])){
                                                $nblit += $re["nb_lit"];
                                            }
                                        } else if (isset($query["arriver"])) {
                                            if ($query["arriver"] >= $re["date_deb"] && $query["arriver"] <= $re["date_fin"]){
                                                $nblit += $re["nb_lit"];
                                            }
                                        } else if (isset($query["depart"])){
                                            if ($query["depart"] >= $re["date_deb"] && $query["depart"] <= $re["date_fin"]){
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
                                        if (($query["arriver"] >= $re["date_deb"] && $query["depart"] <= $re["date_fin"]) 
                                            || ($query["arriver"] <= $re["date_deb"] && $query["depart"] <= $re["date_fin"] && $query["depart"] >= $re["date_deb"])
                                            ||($query["arriver"] >= $re["date_deb"] && $query["arriver"] <= $re["date_fin"] && $query["depart"] >= $re["date_fin"])
                                            || ($query["arriver"] <= $re["date_deb"] && $query["depart"] >= $re["date_fin"])){
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
                            if ($trigger == 0) {
                                $valid[$ch["id_chambre"]] = $ch["id_chambre"];
                            }
                        }
                    }
                }
                ?>
                <div id="findbox" class="box">
                <?php
                foreach ($valid as $cht){
                    echo '<article class="rest">
                    <div class="res">';
                    $reqch = 'SELECT * FROM chambre WHERE id_chambre = '.$cht;
                    $resch = $connexion->prepare($reqch);
                    $bool = $resch->execute();
                    if (!$bool){
                        unset ($resch);
                        unset($res);
                        unset($resch1);
                        unset($resre);
                        unset($connexion);
                        $err = 'Error during server communication';
                        goto fin;                    
                    }
                    $ch = $resch->fetch();
                    $reqc = 'SELECT * FROM hotel WHERE id_hotel = '.$ch["id_hotelch"];
                    $resc = $connexion->prepare($reqc);
                    $bool = $resc->execute();
                    if (!$bool){
                        unset ($resc);
                        unset($res);
                        unset($resch1);
                        unset($resre);
                        unset($connexion);
                        $err = 'Error during server communication';
                        goto fin;                    
                    }
                    $hotel = $resc->fetch();
                    echo '<img src="../src/imgchambre/'.$ch["img"].'" alt="Image de la chambre" class="imgch">
                            <div class="infoch">
                                <p>Hôtel : '.$hotel["nom"].'</p>
                                <p>Qualité : ';
                                    if ($hotel["qualite"] == 1){
                                        echo 'Luxe';
                                    } else if ($hotel["qualite"] == 2){
                                        echo 'Moyen de Gamme';
                                    } else {
                                        echo 'Bas de Gamme';
                                    }
                                    echo '</p>
                                <p>Adresse : '.$hotel["adresse"].'</p>
                            </div>
                            <div class="infoch">
                                <p>Type de chambre : ';
                                if ($ch["is_dortoir"] != 0){
                                    echo 'Dortoir';
                                } else {
                                    echo 'Privative';
                                }
                                echo '</p>
                                <p>Nombre de personne : '.$ch["nb_lits"].'</p>
                                <p>Prix : '.$ch["prix"].'€</p>';
                                affetoile($hotel["etoile"]);?>
                            </div>
                            </div>
                            <div class="divres">
                                <form class="formres" action="../reservation/index.php" method="post">
                                <input type="number" id="idch" name="idch" value="<?php echo $ch["id_chambre"] ?>" required hidden>
                                <input type="number" id="iduser" name="iduser" value="<?php echo $_SESSION["iduser"] ?>" required hidden>
                                <label for="arriver">Date d'arrivée</label>
                                <input type="date" min="2022-01-01" max="2040-01-01" id="arriver" name="arriver"
                                <?php
                                if (isset($query["arriver"])){
                                    echo 'tes';
                                    echo ' value="'.$query["arriver"].'"';
                                }
                                ?>required>
                                <label for="depart">Date de départ</label>
                                <input type="date" min="2022-01-01" max="2040-01-01" name="depart" id="depart"
                                <?php
                                if (isset($query["depart"])){
                                    echo 'value="'.$query["depart"].'"';
                                }
                                ?>required>
                                <input type="number" id="nblit" min="0" name="nblit" placeholder="Nb de lit"
                                <?php
                                if (isset($query["nblit"])){
                                    echo 'value="'.$query["nblit"].'"';
                                }
                                ?>required>
                                <button type="submit">Réserver</button>
                                </form>  
                            </div>
                    </article>
                <?php
                }
                echo'</div>';
            unset ($resc);
            unset($res);
            unset($resch1);
            unset($resre);
            unset($connexion);
            fin:
            echo '</div>';
            if ($err != 0){
                echo '
                <div id="nores" class="box">
                    <p>'.$err.'</p>
                </div>';
            }
            ?>
            </main>
        <footer>
            <span>Cybertel - 2023</span>
        </footer>
    </body>
</html>