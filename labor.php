<?php
	session_start();
?>

<!DOCTYPE html>
<head>
	<title>Labor</title>
	<link rel="stylesheet" type="text/css" href="laborstyle.css" />
</head>
<body>
	<h1>Labor</h1>

	<?php
        if(isset($_SESSION["sicherheitsstufe"]) and $_SESSION["sicherheitsstufe"] >= "2") {
    ?>

	<?php 
	$db = mysqli_connect("localhost", "teststelleuser", "b25GLNKh8EmNolxH", "CDCC");
	
	if (isset($_POST['aktion']) and $_POST['aktion']=='speichern') {
    $testID = "";
    if (isset($_POST['testID'])) {
        $testID = trim($_POST['testID']);
    }
	$inhalt = "";
    if (isset($_POST['inhalt'])) {
        $inhalt = trim($_POST['inhalt']);
    }
	$verantworlicher = "";
    if (isset($_POST['verantworlicher'])) {
        $verantworlicher = trim($_POST['verantworlicher']);
    }
	$ergebnis = "";
    if (isset($_POST['ergebnis'])) {
        $ergebnis = trim($_POST['ergebnis']);
    }
	
	$datum = date("Y-m-d");
	
	//speichern
	$einfuegen = $db->prepare("
                UPDATE test
				SET inhalt = ?, datum = ?, verantwortlicher = ?, ergebnis = ?
                WHERE id = ?
                ");echo $testID;
    $einfuegen->bind_param('sssii', $inhalt, $datum, $verantworlicher, $ergebnis, $testID);
	if ($einfuegen->execute()) {
		header('Location: labor.php');
		die();
	}
	}
	?> 
	<form action="" method="post">
	<label>
	TestID:
	</label>
	<input type="text" name="testID" value="" id="testID">
	<br>
	<label>
	Inhalt:
	</label>
	<input type="text" name="inhalt" value="" id="inhalt">
	<br>
	<label>
	Verantworliche*r:
	</label>
	<input type="text" name="verantworlicher" value="" id="verantworlicher">
	<br>
	<label>
	Ergebnis:
	</label>
	<input type="text" name="ergebnis" value="" id="ergebnis">
	<br>
	<input type="hidden" name="aktion" value="speichern">
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
	