<?php
$log = session_status();
?>

<!DOCTYPE <!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Cybertel</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="./Style/main.css" rel="stylesheet">
    </head>
    <body>
        <header>
            <a href="index.html" id="lienlogo">
                <img id="logo" src="./src/logo.jpg" alt="Logo Cybertel"></img>
            </a>
            <a href="index.html" id="title">Cybertel</a>
            <?php
                if ($log < 2){
                    echo "<a id=\"login\" class=\"log\" href=\"./login\">Log in</a>";
                    echo "<a id=\"register\" class=\"log\" href=\"./register\">Register</a>";
                }else {
                    echo "<a id=\"account\" class=\"log\" href=\"./account\">{$_SESSION['name']} {$_SESSION['prenom']}</a>";
                }
            ?>
        </header>
        <main>
            <div id="mainpart">
                <div id="searchbanner" class="box">
                    <p>
                        Trouver votre hôtel parfait dans toute la France.
                    </p>
                    <form id="searchbar" action="search.php" method="post">
                        <input type="text" id="name" placeholder="Destination">
                        <select id="classification">
                            <option>Qualité de l'hôtel</option>
                            <option value="3">Luxe</option>
                            <option value="2">Moyen</option>
                            <option value="1">Bas</option>
                        </select>
                        <label for="arriver">Date d'arrivée</label>
                        <input type="date" id="arrivee">
                        <label for="depart">Date de départ</label>
                        <input type="date" id="depart">
                        <input type="number" id="nblit" min="0" placeholder="Lits">
                        <button type="submit" id="searchbut">Rechercher</button>
                    </form>
                    <form>
                    </form>
                </div>
            </div>
            <div id="desc" class="box bandeau">
                <img id="descimg" class="img" src="./src/imghotel.jpg" alt="img de description">
                <p>
                    Sur Cybertel vous permet de réserver un chambre d'hôtel dans notre magnifique ville de Night City
                </br>
                                
                </p>
            </div>
            <div id="logement" class="box bandeau">
                <p>
                    La particularité des hôtels proposants leur chambres ne sont que des hôtels ayant subit les affres de la guerre.
                </p>
                <img id="imgchamb" class="img" src="./src/imgchambre.jpg" alt="Chambre hôtel">
            </div>
            <div id="avertissement" class="box bandeau">
                <p id="police">
                    Night city.
                </p>
                <img id="imgaver" class="img" src="./src/avertimg.jpg" alt="Voiture police">
                <p id="trauma">
                    La Trauma Team.
                </p>
            </div>
        </main>
        <footer>
            <span>Cybertel - 2023</span>
        </footer>
    </body>
</html>