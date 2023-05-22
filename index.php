<?php
    session_start();
?>

<!DOCTYPE <!DOCTYPE html5>
<html lang="fr">
    <head>
        <title>Cybertel</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="./Style/main.css" rel="stylesheet">
        <link href="./Style/base.css" rel="stylesheet">
    </head>
    <body>
        <header>
            <a href="index.php" id="lienlogo">
                <img id="logo" src="./src/logo.jpg" alt="Logo Cybertel"></img>
            </a>
            <a href="index.php" id="title" class="neonText">Cybertel</a>
            <?php
                if (!isset($_SESSION["nom"])){
                    echo '<a id="login" class="log" href="./login">Se connecter</a>';
                    echo '<a id="register" class="log" href="./register">S\'inscrire</a>';
                }else {
                    echo '<a id="account" class="log" href="./account">'.$_SESSION["nom"].' '.$_SESSION["prenom"].'</a>';
                }
            ?>
        </header>
        <main>
            <div id="mainpart">
                <div id="searchbanner" class="box">
                    <p>
                        Trouver votre chambre d'hôtel parfait à Night City.
                    </p>
                    <form id="searchbar" action="./search" method="post">
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
                    Sur Cybertel vous permet de réserver une chambre d'hôtel dans notre magnifique ville de Night City.
                    Night City est une ville qui respire la liberté, ici, vous pouvez tous faire du simple tourisme dans les quartiers 
                    les plus touristiques de la ville, tel que <span>Wellsprings</span> situer dans le quartier de <span>Heywood</span> 
                    ou bien <span>JapanTown</span> dans le quartier de <span>Westbrook</span>  un secteur assez chic dédié au divertissement,
                    avec ses bars, ses restaurants, ses boutiques de luxe où les touristes fortunés aiment flâner, mais aussi des casinos et autres clubs.
                    Vous pourrez occasionnellement trouver sur notre site des offres pour des chambres situé dans des motels des <span>badlands</span>. 
                    Les badlands ne sont pas un réel quartier de la ville, c'est une zone désertique située autour de la ville, 
                    c'est une grande plaine de sable avec des dunes qui pour les amoureux du rallye est un paradis.        
                </p>
            </div>
            <div id="logement" class="box bandeau">
                <p>
                    Cybertel vous propose 3 types de chambres, la chambre de luxe, principalement situé dans les hôtels vers le <span>centre-ville</span> 
                    Principalement dominer par les immeubles de Arasaka.
                    La chambre moyenne, situé dans les hôtels de <span>Westbrook</span> et <span>Heywood</span> 
                    et enfin la chambre bas de gamme, situé dans les hôtels de <span>Watson</span> et <span>Pacifica</span>.
                </p>
                <img id="imgchamb" class="img" src="./src/imgchambre.jpg" alt="Chambre hôtel">
            </div>
            <div id="avertissement" class="box bandeau">
                <p id="police">
                    Lors de votre séjour à night city, faites attention à ne pas vous faire tuer, 
                    car la ville est gangréné par les gangs et les corporations qui n'esiterons pas à vous tuer si ele pense que vous pouvez être une menace à leur business. 
                    car la police de Night City est très corrompue et ne fera rien pour vous aider, à moins que cous ayez la capacité de les payer plus cher que les corporations.
                </p>
                <img id="imgaver" class="img" src="./src/avertimg.jpg" alt="Voiture police">
                <p id="trauma">
                    Pour votre sécurité, nous vous conseillons de souscrire à un abonnement platine de la 7
                    <span>trauma team</span> qui vous permettra d'être secouru en cas de blessure grave.
                    La Trauma Team est réellement craint à Night City, car elle est connue pour venir secourir ses clients, même si cela signifie de tuer des innocents.
                    Donc si vous ne souscrivez pas à un abonnement, ne leur barrez surtout pas la route.
                </p>
            </div>
        </main>
        <footer>
            <span>Cybertel - 2023</span>
        </footer>
    </body>
</html>