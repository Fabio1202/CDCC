<?php
session_start();
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Baloo+2:400,500,600,700,800&display=swap" rel="stylesheet">
    <title>Document</title>

</head>

<body>




    <?php
    $db = mysqli_connect("localhost", "loginuser", "p175BKYlVQZLytCB", "cdcc");
    if (isset($_GET['login']) && $_GET['login'] == "true") {
        $passwort = $_POST['pw'];
        $erg = $db->query("SELECT zugriffsstufe, passwort FROM zugang WHERE name = '" . $_POST['name'] . "'");
        if ($erg->num_rows > 0) {
            while ($row = $erg->fetch_assoc()) {
                $spw = $row['passwort'];
                $zugriffsstufe = $row['zugriffsstufe'];
            }
        } else {
            die();
        }
        if (password_verify($passwort, $spw)) {
            echo "Zugriff gewährt. Sicherheitstufe: ";
            echo $zugriffsstufe;
            $_SESSION["username"] = $_POST["name"];
            $_SESSION["sicherheitsstufe"] = $zugriffsstufe;
        }
    }
    ?>

    <nav>
        <span id="closeNav"><svg xmlns="http://www.w3.org/2000/svg" width="43.409" height="43.409" viewBox="0 0 43.409 43.409">
                <g transform="translate(2.7046301 2.7046301)">
                    <path fill="none" stroke="rgb(0,0,0)" stroke-width="2" d="M36 2.00576214L2 36" />
                    <path fill="none" stroke="rgb(0,0,0)" stroke-width="2" d="M2.00010059 2l33.98056726 34" />
                </g>
            </svg></span>

        <div class="navContent">
            <h1 onclick="window.location = '#'">Informationen</h1>
            <h1 onclick="window.location = '#'">Teststelle finden</h1>
            <h1 onclick="window.location = 'https://www.rki.de'">Robert-Koch-Institut</h1>
            <h1 onclick="window.location = '#'">Datenschutz</h1>
            <h1 onclick="window.location = '#'">Impressum</h1>
        </div>

        <span class="goToLogin" onclick="window.location = 'statusFirstStep.html'">Teststatus einsehen</span>
        <h3 onclick="window.location = 'rki.php'">Login für Behörden</h3>
    </nav>

    <div id="landing" style="height:120vh; overflow: none" class="firstStepVerify rki">
        <span id="navIcon"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 43.409 30.409">
                <g transform="translate(4.7046301 4.7046301)">
                    <path fill="none" stroke="rgb(255,255,255)" stroke-width="2" d="M34 0H0" />
                    <path fill="none" stroke="rgb(255,255,255)" stroke-width="2" d="M34 10.5H0" />
                    <path fill="none" stroke="rgb(255,255,255)" stroke-width="2" d="M34 21H0" />
                </g>
            </svg></span>
        <span class="background-image" style="background-image: url('images/louis-reed-pwcKF7L4-no-unsplash.jpg');"></span>
        <span class="graphic-left"></span>
        <h1>GEBEN SIE<br />IHRE DATEN EIN</h1>
        <form action="rki.php?login=true" method="post">
            <input type="text" name="name" id="token" placeholder="Username">
            <input type="password" name="pw" placeholder="Passwort">
            <button type="submit" name="submit" id="submit" onclick="ajax()">Anmelden</button>
        </form>
    </div>


</body>
<script>
    document.getElementById("navIcon").onclick = function() {
        document.getElementsByTagName("nav")[0].style.left = 0;
        document.getElementsByTagName("nav")[0].style.backgroundColor = "white";
    };

    document.getElementById("closeNav").onclick = function() {
        document.getElementsByTagName("nav")[0].style.left = "-100vw";
        document.getElementsByTagName("nav")[0].style.backgroundColor = "black";
    };
</script>

</html>