<?php
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
        <link href="../Style/reservation.css" rel="stylesheet">
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
            <div id="mainpart">
            <?php
            if (!isset($_SESSION["nom"])){
                echo '<div class="box nores">
                            <p>Vous n\'êtes pas connecté !</p>
                            <hr>
                            <p>Veuillez vous connecter</p>
                            <a href="../login" class="login">Se connecter </a>
                        </div>';
                goto fin;
            }
            $err = 0;
            if (isset($_POST["idch"], $_POST["iduser"], $_POST["arriver"], $_POST["depart"], $_POST["nblit"])){
                if (!is_numeric($_POST["idch"]) || !is_numeric($_POST["iduser"]) || !is_numeric($_POST["nblit"])){ 
                    unset($_POST["idch"], $_POST["iduser"], $_POST["nblit"]);}
                if ($_POST["arriver"] == "" || $_POST["depart"] == ""){
                    unset($_POST["arrivee"], $_POST["depart"]);
                }
                if ($_POST["arriver"] > $_POST["depart"]){
                    unset($_POST["arrivee"], $_POST["depart"]);
                }
                if (!isset($_POST["idch"], $_POST["iduser"], $_POST["arriver"], $_POST["depart"], $_POST["nblit"])){
                    $err = "bad format";
                    goto fin;
                }
            include "../src/mysql.php";
            try {
                $connexion = new PDO ('mysql:host='.MYSQL_HOST.';port=3306;dbname='.MYSQL_DB, MYSQL_LOG, MYSQL_PWD);
            } catch (PDOException $e){
                $err = "Error during server connection";                    
            goto fin;
            }
            $req = 'SELECT * FROM chambre WHERE id_chambre = :id;';
            $resch1 = $connexion->prepare($req);
            $resch1->bindParam(':id', $_POST["idch"]);
            $bool = $resch1->execute();
            if (!$bool){
                unset($resch1);
                unset($connexion);
                $err = "Error during server communication";
                goto fin;                    
            }
            $ch = $resch1->fetch();
            $reqre = 'SELECT * FROM reservation WHERE id_chambre = '.$ch["id_chambre"].';';
            $resre = $connexion->prepare($reqre);
            $bool = $resre->execute();
            if (!$bool){
                unset($resch1);
                unset($resre);
                unset($connexion);
                $err = "Error during server communication";
                goto fin;                    
            }
            $allre = $resre->fetchAll();
            $nblit = 0;
            foreach ($allre as $re){
                if ($ch["is_dortoir"] != 0){
                    if (($_POST["arriver"] >= $re["date_deb"] && $_POST["depart"] <= $re["date_fin"]) 
                        || ($_POST["arriver"] <= $re["date_deb"] && $_POST["depart"] <= $re["date_fin"] && $_POST["depart"] >= $re["date_deb"])
                        || ($_POST["arriver"] >= $re["date_deb"] && $_POST["arriver"] <= $re["date_fin"] && $_POST["depart"] >= $re["date_fin"])
                        || ($_POST["arriver"] <= $re["date_deb"] && $_POST["depart"] >= $re["date_fin"])){
                         $nblit += $re["nb_lit"];
                    }
                    if ($nblit + $_POST["nblit"] >= $ch["nb_lits"]) {
                        unset($resch1);
                        unset($resre);
                        unset($connexion);
                        $err = "
                        La chambre n'est pas disponible pour cette période 
                        Veuillez changer vos date de réservation";
                        goto fin;
                    } 
                } else {
                    if (($_POST["arriver"] >= $re["date_deb"] && $_POST["depart"] <= $re["date_fin"]) 
                        || ($_POST["arriver"] <= $re["date_deb"] && $_POST["depart"] <= $re["date_fin"] && $_POST["depart"] >= $re["date_deb"])
                        || ($_POST["arriver"] >= $re["date_deb"] && $_POST["arriver"] <= $re["date_fin"] && $_POST["depart"] >= $re["date_fin"])
                        || ($_POST["arriver"] <= $re["date_deb"] && $_POST["depart"] >= $re["date_fin"])){
                        unset($resch1);
                        unset($resre);
                        unset($connexion);
                        $err = "
                        La chambre n'est pas disponible pour cette période 
                        Veuillez changer vos date de réservation";
                        goto fin;
                    }
                }
            }
                $resrev = "INSERT INTO reservation (id_chambre, id_user, date_deb, date_fin, nb_lit) VALUES (:idch, :iduser, :arriver, :depart, :nblit);";
                $resrev = $connexion->prepare($resrev);
                $resrev->bindParam(':idch', $_POST["idch"]);
                $resrev->bindParam(':iduser', $_POST["iduser"]);
                $resrev->bindParam(':arriver', $_POST["arriver"]);
                $resrev->bindParam(':depart', $_POST["depart"]);
                $resrev->bindParam(':nblit', $_POST["nblit"]);
                $bool = $resrev->execute();
                if (!$bool){
                    unset($resch1);
                    unset($resre);
                    unset($resrev);
                    unset($connexion);
                    $err = "Error during server communication";
                    goto fin;                    
                }
                echo '
                <div class="box nores">
                <p>
                Votre réservation a bien été prise en compte
                Toute les informations sur la chambre son sur votre comtpe</p>
                <hr>
                <p>Votre compte</p>
                <a class="login" href="../account">Votre compte</a>
                </div>';
                unset($resch1);
                unset($resre);
                unset($resrev);
                unset($connexion);
            } else {
                $err = '
                Vous n\'avez fait aucune résérvation';
            }
            fin:
            if ($err != 0){
                echo '<div class="box nores">
                <p class="errmsg">
                '.$err.'</p>
                <hr>
                <p>Pour faire une réservation</p>
                <a class="login" href="../find">Faire une réservation</a>
                <hr>
                <p>Votre compte</p>
                <a class="login" href="../account">Votre compte</a>
                </div>';
            }
            echo '</div>';
            ?>
        </main>
        <footer>
            <span>Cybertel - 2023</span>
        </footer>
    </body>
</html>