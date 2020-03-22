<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status</title>
    <link rel="stylesheet" href="css/style.css">
    <title>Covid Testergebnis online</title>
    <meta name="description" content="Dies ist die zentrale Datenbank zur Verwaltung von Covid-19 Tests. Neben dem Nutzen für Labore, Gesundheitsämter und Teststellen
    werden auch Sie durch die Datenbank über den Status Ihres Tests informiert.">
    <link href="https://fonts.googleapis.com/css?family=Baloo+2:400,500,600,700,800&display=swap" rel="stylesheet">
</head>

<body class="status">



    <?php
    if (isset($_SESSION['val'])) {
        $testid = $_SESSION['val'];

        $db = mysqli_connect("localhost", "loginuser", "p175BKYlVQZLytCB", "cdcc");

        $sql = $db->prepare("SELECT inhalt, datum, ergebnis FROM test WHERE id = ?");
        $sql->bind_param('i', $testid);
        $sql->execute();
        $res = $sql->get_result();
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $inhalt = $row['inhalt'];
                $datum = $row['datum'];
                $ergebnis = $row['ergebnis'];
            }
        } else {
            die();
        }
    ?>
        <h1>Status</h1>
        <br />
        <br />
        <h2>Ergebnis: <br> <?php
                        switch ($ergebnis) {
                            case '-1':
                                echo "In Bearbeitung";
                                break;
                            case '0':
                                echo "Negativ";
                                break;
                            case '1':
                                echo "Positiv";
                                break;
                            default:
                                echo "Fehler";
                        }
                        ?></h2>
        <br />
        <br />
        <h3>Inhalt: <br> <?php echo $inhalt; ?></h3>
        <br />
        <h3>Datum: <br> <?php echo $datum; ?></h3>
    <?php
    } else {
    ?>
        <p>Sie sind nicht angemeldet!...<a href="index.html">Zur Startseite</a></p>
    <?php
    }
    ?>
</body>

</html>