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

<!DOCTYPE <!DOCTYPE html5>
<html lang="fr">
    <head>
        <title>Cybertel</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="../script/jquery-3.7.0.min.js"></script>
        <script src="../script/account.js"></script>
        <link href="../Style/account.css" rel="stylesheet">
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
                    <h2>Information Personnel</h2>
                    <div id="info">
                        <p>Nom : <?php echo $_SESSION["nom"]?></p>
                        <p>Prenom : <?php echo $_SESSION["prenom"]?></p>
                        <p>Email : <?php echo $_SESSION["email"]?></p>
                        <p>Téléphone : <?php echo $_SESSION["tel"]?></p>
                        <p>Adresse : <?php echo $_SESSION["adresse"]?></p>
                        <p>Code postal : <?php echo $_SESSION["codepost"]?></p>
                    </div>
                    <h3>Changer de mot de passe</h3>
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
                        $connexion = new PDO ('mysql:host='.MYSQL_HOST.';port=3306;dbname='.MYSQL_DB, MYSQL_LOG, MYSQL_PWD);
                    } catch (PDOException $e){
                        session_destroy();
                        $_SESSION = [];
                        $err = "Error during server connection";
                        goto fin;
                    }
                    $req = "SELECT * FROM reservation WHERE id_user = (SELECT id_user FROM user WHERE email = :email);";
                    $res = $connexion->prepare($req);
                    $res->bindParam(':email', $_SESSION["email"]);
                    $bool =  $res->execute();
                    if (!$bool){
                        session_destroy();
                        $_SESSION = [];
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
                            <a href="../find" class="login">Reserver votre chambre !</a>
                        </div>';
                        goto fin;
                    }
                    ?>
                    <p id="titreres">Vos réservations</p>
                    <div id="rest" class="box">
                    <?php
                        foreach ($allresv as $resv) {
                            echo '<article class="res">';
                            $reqch = 'SELECT * FROM chambre WHERE id_chambre = '.$resv["id_chambre"];
                            $resch = $connexion->prepare($reqch);
                            $boolch =  $resch->execute();
                            if (!$boolch){
                                session_destroy();
                                $_SESSION = [];
                                unset($resch);
                                unset($connexion);
                                $err = "Error during server communication";
                                goto fin;
                            }
                            $chambre = $resch->fetch();
                            $reqhotel = "SELECT * FROM hotel WHERE id_hotel = '{$chambre["id_hotelch"]}';";
                            $reshotel = $connexion->prepare($reqhotel);
                            $boolho =  $reshotel->execute();
                            if (!$boolho){
                                session_destroy();
                                $_SESSION = [];
                                unset($resch);
                                unset($reshotel);
                                unset($connexion);
                                $err = "Error during server communication";
                                goto fin;
                            }
                            $hotel = $reshotel->fetch();
                            echo '<img src="../src/imgchambre/'.$chambre["img"].'" alt="Image de la chambre" class="imgres">
                                <div class="infores">
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
                                    <p>Prix : '.$chambre["prix"].'€</p>';
                                    affetoile($hotel["etoile"]);?>
                                <?php echo '
                                </div>
                                <div class="infores">
                                    <p> Numéro de la chambre : '.$chambre["nb_chambre"].'</p>
                                    <p>Type de chambre : ';
                                    if ($chambre["is_dortoir"] == true){
                                        echo 'Dortoir';
                                    } else {
                                        echo 'Privative';
                                    }
                            echo '</p>
                                    <p>Arrivée : '.$resv["date_deb"].'</p>
                                    <p>Départ : '.$resv["date_fin"].'</p>
                                    <p>Nombre de personne : '.$resv["nb_lit"].'</p>
                                 </div>';
                            echo '</article>';
                        }
                        echo '</div>';

                    fin:
                ?>
            </div>
        </main>
        <footer>
            <span>Cybertel - 2023</span>
        </footer>
    </body>
</html>