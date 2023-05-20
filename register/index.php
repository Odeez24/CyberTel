<!DOCTYPE <!DOCTYPE html5>
<html lang="fr">
    <head>
        <title>Cybertel</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="../script/register.js"></script>
        <script src="../script/jquery-3.7.0.min.js"></script>
        <link href="../Style/register.css" rel="stylesheet">
    </head>
    <body>
        <header>
            <a href="../index.php" id="lienlogo">
                <img id="logo" src="../src/logo.jpg" alt="Logo Cybertel"></img>
            </a>
            <a href="../index.php" id="title">Cybertel</a>
        </header>
        <main>
            <?php
                $err = 0;
                $firstlaunch = 0;
                $noinfo = false;
                if (isset($_POST["nom"], $_POST["prenom"], $_POST["email"], $_POST["mdp"], $_POST["tel"])){
                    if (preg_match("/^[A-Za-z^\s][A-Za-z\s]{1,14}[A-Za-z^\s]/", $_POST["nom"]) != 1){ unset($_POST["nom"]);}
                    if (preg_match("/^[A-Za-z^\s][A-Za-z\s]{1,14}[A-Za-z^\s]/", $_POST["prenom"]) != 1){ unset($_POST["prenom"]);}   if (preg_match("/^.{0,128}$/", $_POST["email"]) != 1){ unset($_POST["email"]);}
                    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){ unset($_POST["email"]);}
                    if (preg_match("/^/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/", $_POST["mdp"]) != 1){ unset($_POST["msp"]);}
                    if (preg_match("/^((\+|00)33\s?|0)[67](\s?\d{2}){4}$/", $_POST["tel"]) != 1){unset($_POST["tel"]);}
                    if (!isset($_POST["nom"], $_POST["prenom"], $_POST["email"], $_POST["mdp"], $_POST["tel"])){
                        $err = "Bad Format";
                        goto fin;
                    }
                    include "../src/mysql.php";
                    try {
                        $connexion = mysqli_connect(MYSQL_HOST, MYSQL_LOG, MYSQL_PWD, MYSQL_DB);
                    } catch (Exception $e){
                        $err = "Error during server connection";
                        goto fin;
                    }
                    if (mysqli_connect_error() != null){
                        $err = "Internal Serveur error.";
                        mysqli_close($connexion);
                        goto fin;
                    }

                    try {
                        mysqli_query($connexion, "INSERT INTO user (nom, prenom, email, password, tel) VALUES ('{$_POST["nom"]}', '{$_POST["prenom"]}', '{$_POST["email"]}', '{$_POST["mdp"]}', '{$_POST["tel"]}')");
                    } catch (Exception $e) {
                        $msg = $e->getMessage();
                        if ($msg == "Duplicate entry '".$_POST["email"]."' for key 'email'"){
                            unset($_POST["email"]);
                            $err = "Account already exist";
                        } else {
                            $err = $msg;
                        }
                        unset($_POST["mdp"]);
                        mysqli_close($connexion);
                        goto fin;
                    }
                    $firstlaunch = 1;
                    fin:
                    
                } else {
                    $noinfo = true;
                }
            ?>
            <div id="validate" class="box"
                <?php
                if ($firstlaunch == 0){
                    echo "hidden";
                }
                ?>>
                <p>Félicitations, vous êtes maintenant inscrits.</p>
                <hr>
                <p>Connecter vous</p>
                <a id="login" href="../login">Login</a>
            </div>

            <div id="register" class="box"
                <?php
                    if ($firstlaunch != 0) {
                        echo "hidden";
                    }
                ?>>
                <span>Register</span>
                <form action="./" method="post" name="register">
                    <?php 
                        if ($err != 0){
                            echo "<p class=\"err errmsg\">".$err."</p>";
                        }
                    ?>
                    <label for="nom">Minimun 3 caractères, maximun 17 et pas de caractère spécial.</label>
                    <input type="text" id="nom" name="nom" placeholder="Nom" pattern="[A-Za-z^\s][A-Za-z\s]{1,14}[A-Za-z^\s]"
                    <?php 
                    if (isset($_POST["nom"])){
                        $a = $_POST["nom"];
                        echo "value = \"{$a}\"";
                    }else if (!$noinfo) {
                        echo "class=\"err\"";
                    }
                    ?>required>
                    <label for="prenom">Minimun 3 caractères, maximun 17 et pas de caractère spécial.</label>
                    <input type="text" id="prenom" name="prenom" placeholder="Prenom" pattern="[A-Za-z^\s][A-Za-z\s]{1,14}[A-Za-z^\s]"
                    <?php 
                    if (isset($_POST["prenom"])){
                        $a = $_POST["prenom"];
                        echo "value = \"{$a}\"";
                    }else if (!$noinfo) {
                        echo "class=\"err\"";
                    }
                    ?> required>
                    <input type="email" id="email" name="email" placeholder="****@****.com" 
                    <?php 
                    if (isset($_POST["email"])){
                        $a = $_POST["email"];
                        echo "value = \"{$a}\"";
                    }else if (!$noinfo) {
                        echo "class=\"err\"";
                    }
                    ?>required>
                    <label>Minimum 8 caractères, au moins une majuscule, une minuscule et une lettre</label>
                    <input type="password" id="mdp" name="mdp"placeholder="password" pattern="(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$"
                    <?php 
                    if (isset($_POST["mdp"])){
                        $a = $_POST["mdp"];
                        echo "value = \"{$a}\"";
                    }else if (!$noinfo) {
                        echo "class=\"err\"";
                    }
                    ?>required>
                    <input type="tel" id="tel" name="tel" placeholder="(0x ou +33)" pattern="((\+|00)33\s?|0)[67](\s?\d{2}){4}$"
                    <?php 
                    if (isset($_POST["tel"])){
                        $a = $_POST["tel"];
                        echo "value = \"{$a}\"";
                    }else if (!$noinfo) {
                        echo "class=\"err\"";
                    }
                    ?>required>
                    <button type="submit">Register</button>
                </form>

            <hr>
            <p>Vous avez déjà un compte ?</p>
            <a id="login" href="../login">Login</a>
            </div>
        </main>
        <footer>
            <span>Cybertel - 2023</span>
        </footer>
    </body>
</html>