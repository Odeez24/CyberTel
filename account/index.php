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
                    echo '<a id="account" class="log" id="loga" href="./account">'.$_SESSION["nom"].' '.$_SESSION["prenom"].'</a>';
                }
            ?>
            <a href="../logout/logout.php" class="log" id="logout">Déconnexion</a>
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
                        <p>Adresse : <?php echo $_SESSION["adresse"]?></p>
                    </div>
                    <p>Changer de mot de passe</p>
                    <p>Minimum 8 caractères, au moins une majuscule, une minuscule et une lettre</p>
                    <div id="changemdp">
                        <form action="./" method="post">
                            <input type="password" id="existmdp"name="existmdp" placeholder="Ancien mot de passe" pattern="(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$">
                            <input type="password" id="newmdp"name="newmdp" placeholder="Nouveaux mot de passe" pattern="(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$">
                            <input type="password" id="newmdp2"name="newmdp2" placeholder="Confirmation" pattern="(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$">
                            <img src="../src/closeeyes.jpg" id="eyes" onclick="viewmdp()">
                            <button type="submit">Changer</button>
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
                <?php
                    $err = 0;
                    include "../src/mysql.php";
                    try {
                        $connexion = new PDO ('mysql:host='.MYSQL_HOST.';port=3306;dbname='.MYSQL_DB.'', MYSQL_LOG, MYSQL_PWD);
                    } catch (PDOException $e){
                        session_destroy();
                        $err = "Error during server connection";
                        goto fin;
                    }
                    $req = "SELECT * FROM reservation WHERE id_user = (SELECT id_user FROM user WHERE email = '{$_SESSION["email"]}')";
                    $res = $connexion->prepare($req);
                    $bool =  $res->execute();
                    if (!$bool){
                        session_destroy();
                        unset($res);
                        unset($connexion);
                        $err = "Error during server communication";
                        goto fin;
                    }
                    $allresv = $res->fetchAll();
                    if (count($allresv) == 0){
                        unset($allresv);
                        unset($connexion);
                        echo '
                        <div id="nores" class="box">
                            <p>Vous n\'avez pas de réservation !</p>
                            <hr>
                            <p>Si vous voulez resérver une chambre veillez vous rentre sur cette page</p>
                            <a href="../search" class="login">Reserver votre chambre !</a>
                        </div>';
                        goto fin;
                    } else {
                        echo '<div id="rest" class="box">
                            <p>Vos réservations</p>
                            <hr>';
                        foreach ($allresv as $resv) {
                            echo '<article class="res">';
                            $reqch = "SELECT * FROM Chambre WHERE id_chambre = '{$resv["id_chambre"]}';";
                            $resch = $connexion->prepare($reqch);
                            $boolch =  $resch->execute();
                            $reqhotel = "SELECT * FROM Hotel WHERE id_hotel = '{$chambre["id_hotel"]}';";
                            $reshotel = $connexion->prepare($reqhotel);
                            $boolho =  $reshotel->execute();
                            if (!$boolch || !$boolho){
                                session_destroy();
                                unset($resch);
                                unset($reshotel);
                                unset($connexion);
                                echo "Error during server communication";
                                goto fin;
                            }
                            $chambre = $resch->fetch();
                            $hotel = $reshotel->fetch();
                            echo '<img src="../src/imgchambre/".$chambre[\"img\"].".jpg" alt="Image de la chambre" class="imgres">
                                <div class="infores">
                                    <p>Hôtel : '.$hotel["nom"].'</p>
                                    <p>Qualité : '.$hotel["qualiter"].'</p>
                                    <p>Adresse : '.$hotel["adresse"].'</p>
                                </div>
                                <div class="infores">
                                    <p>Type de chambre : ';
                                    if ($chambre["is_dortoir"] == true){
                                        echo 'Dortoir';
                                    } else {
                                        echo 'Chambre privative';
                                    }
                            echo '</p>
                                    <p>Arrivée : '.$resv["date_arrivee"].'</p>
                                    <p>Départ : '.$resv["date_depart"].'</p>
                                    <p>Nombre de personne : '.$resv["nb_lit"].'</p>
                                    <p>Prix : '.$resv["prix"].'€</p>
                                 </div>';
                            echo '</article>';
                        }
                    }

                    fin:
                ?>
            </div>
        </main>
        <footer>
            <span>Cybertel - 2023</span>
        </footer>
    </body>
</html>