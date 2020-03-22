<?php
    session_start();
    $verhalten = 0;
    
    if(!isset($_SESSION["username"]) and !isset($_GET["page"])) {
        $verhalten = 0;
    }
    if(isset($_GET['page']) && $_GET['page'] == "log") {
        
        $user =strtolower ($_POST["user"]);
        $passwort = $_POST["pw"];
        
        echo "USER:::::::::::: n       -".$user;
        echo "PASS:::::::::::: n       -".$passwort;

        $verbindung = mysqli_connect("localhost", "root", "NuY1Yq1jeA3U", "cdcc")
        or die ("Fehler im System");
        
        $result = $verbindung->prepare("SELECT passwort FROM zugang WHERE name=?");
        $result->bind_param('s', $user);
        $result->execute();

        $erg = $result->get_result();
        
        if ($erg->num_rows > 0) {
            while($row = $res->fetch_assoc()) {
                $spw = $row['passwort'];
            }
        } else {
            die();
        }
        if (password_verify($passwort, $spw)) {
            echo "Zugriff gewährt. Sicherheitstufe: ";
            $_SESSION["username"] = $user;
            $verhalten = 1;
        } else {
            $verhalten = 2;
        }


    }
?>
<!DOCTYPE html>
<html lang="de">
    
    <head>
        <?php
            
            if($verhalten == 1) {
                echo "endlich funktioniert der kack!!!";
            }
        ?>
    </head>
    <body>
        
        
        <br />
        <br />
        <br />
        <br />
        
        <?php
            if($verhalten == 0) {
        ?>  
        
        <h1>Login</h1>
        
        <div id="LoginFormbox">
            <form method="post" action="login.php?page=log" id="LoginForm">
                <input type="text" placeholder="Benutzername" name="user"/>
                <br />
                <br />
                <input type="password" placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;" name="pw"/>
                <br />
                <br />
                <button id="button_login" type="submit"><a>Login</a></button>
            </form>
        </div>
        
        
        <?php
            }
            if($verhalten == 1) {
        ?>
        <br />
        <br />
        <br />
        <p class="weiterleitung">Du hast dich erfolgreich eingeloggt und wirst jetzt weitergeleitet ...</p>
        
        <?php
            }
            if($verhalten == 2) {
        ?>
        
        <h1>Login</h1>
        
        <div id="LoginFormbox">
            <form method="post" action="login.php?page=log" id="LoginForm">
                <input type="text" placeholder="Benutzername" name="user"/>
                <br />
                <br />
                <input type="password" placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;" name="pw"/>
                <br />
                <br />
                <button id="button_login" type="submit"><a>Login</a></button>
                
            </form>
        </div>
        <p>Du hast den Falschen Benutzernamen oder das falsch Passwort eingegebe. Wiederhole deine Eingabe!!</p>
        
        
        <?php
            }
        ?>
    </body>
</html>