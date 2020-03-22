<?php
    session_start();

    require 'aws/aws-autoloader.php';

    use Aws\Sns\SnsClient;
    use Aws\Exception\AwsException;
?>

<!DOCTYPE html>
<head>
	<title>Teststelle</title>
	<link rel="stylesheet" type="text/css" href="teststellestyle.css" />
</head>
<body>
    <h1>Teststelle</h1>
    <?php
        if(isset($_SESSION["sicherheitsstufe"]) and $_SESSION["sicherheitsstufe"] >= "1") {
    ?>

    <?php 
        echo "test";
        $db = mysqli_connect("localhost", "teststelleuser", "b25GLNKh8EmNolxH", "CDCC");
        
        if (isset($_POST['aktion']) and $_POST['aktion']=='speichern') {
        $vorname = "";
        if (isset($_POST['vorname'])) {
            $vorname = trim($_POST['vorname']);
        }
        $nachname = "";
        if (isset($_POST['nachname'])) {
            $nachname = trim($_POST['nachname']);
        }
        $telefon = "";
        if (isset($_POST['telefon'])) {
            $telefon = trim($_POST['telefon']);
        }
        $plz = "";
        if (isset($_POST['plz'])) {
            $plz = trim($_POST['plz']);
        }
        $stadt = "";
        if (isset($_POST['stadt'])) {
            $stadt = trim($_POST['stadt']);
        }
        $adresse = "";
        if (isset($_POST['adresse'])) {
            $adresse = trim($_POST['adresse']);
        }
        $mail = "";
        if (isset($_POST['mail'])) {
            $mail = trim($_POST['mail']);
        }
        $laborID = "";
        if(isset($_POST['laborid'])) {
            $laborID = trim($_POST['laborid']);
        }
        
        //speichern
        $einfuegen = $db->prepare("
                    INSERT INTO patient (vorname, nachname, telefon,plz,stadt,adresse,mail) 
                    VALUES (?,?,?,?,?,?,?)
                    ");
        $einfuegen->bind_param('sssisss', $vorname, $nachname,$telefon,$plz,$stadt,$adresse,$mail);
        if (!$einfuegen->execute()) {
            echo "Error: Patient could not be inserted";
            die();
        }

        $select = $db->prepare("SELECT id FROM patient WHERE vorname = ? AND nachname = ? AND telefon = ? AND plz = ? AND stadt = ? AND adresse = ? AND mail = ?");
        $select->bind_param('sssisss', $vorname, $nachname, $telefon, $plz, $stadt, $adresse, $mail);
        if ($select->execute()) {
            $result = $select->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['id'];
                }
            } else {
                echo "Error: Patient could not be found";
                die();
            }
        }

        $date = date("Y.m.d");

        echo $id;
        $insert = $db->prepare("INSERT INTO test (inhalt, datum, verantwortlicher, laborid, patientid, ergebnis) VALUES ('In Bearbeitung',?,'',?,?,'-1')");
        $insert->bind_param("sii", $date, $laborID, $id);
        if(!$insert->execute()) {
            echo "Error: New test could not be inserted #2";
            die();
        }

        $select = $db->prepare("SELECT id FROM test WHERE inhalt='In Bearbeitung' AND datum=? AND verantwortlicher='' AND laborid=? AND patientid=? AND ergebnis='-1'");
        $select->bind_param("sii",$date, $laborID, $id);
            if ($select->execute()) {
                $result = $select->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $testID = $row['id'];
                    }
                } else {
                    echo "Error: Patient could not be found";
                    die();
                }
            }

        while(strlen($testID) < 6) {
            $testID = "0".$testID;
        }

        $SnSclient = new SnsClient([
            'profile' => 'default',
            'region' => 'us-east-1',
            'version' => '2010-03-31'
        ]);

        $message = "Hallo, ".$vorname." ".$nachname.". Die eindeutige ID ihres Covid-19 Tests lautet: ".$testID;

        try {
            $result = $SnSclient->publish([
                'Message' => $message,
                'PhoneNumber' => $telefon,
            ]);
            $_SESSION['testID'] = $id;
            echo "true";
        } catch (AwsException $e) {
            echo ($e->getMessage());
            // output error message if fails
            error_log($e->getMessage());
            die();
        } 

    }
	?> 
       
	<form action="" method="post">
	<label>
	Vorname:
	</label>
	<input type="text" name="vorname" value="" id="vorname">
	<br>
	<br>
	<label>
	Nachname:
	</label>
	<input type="text" name="nachname" value="" id="nachname">
	<br>
	<br>
	<label>
	Telefon:
	</label>
	<input type="text" name="telefon" value="" id="telefon">
	<br>
	<br>
	<label>
	PLZ:
	</label>
	<input type="text" name="plz" value="" id="plz">
	<br>
	<br>
	<label>
	Stadt:
	</label>
	<input type="text" name="stadt" value="" id="stadt">
	<br>
	<br>
	<label>
	Adresse:
	</label>
	<input type="text" name="adresse" value="" id="adresse">
	<br>
	<br>
	<label>
	Mail:
	</label>
	<input type="text" name="mail" value="" id="mail">
	<br>
	<br>
    <input type="hidden" name="aktion" value="speichern">
	<label>
	Labor-ID
	</label>
	<input type="text" name="laborid" value="" id="laborid">
    <input type="submit" value="speichern">
	</form> 

        
    <?php
        } else {
    ?>
    <h2>Sie haben nicht die nötige Berechtigung um auf diese Seite zu zu greifen!</h2>
    <a href="index.html">Startseite</a>
    <?php
        }
    ?>
        

</body>
	