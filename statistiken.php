<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="de">
<head>
	<title>Statistiken</title>
	<link rel="stylesheet" type="text/css" href="\css\style.css" />
</head>
<body>
	<h1>Statistiken</h1>	
	<?php		
	
	if(isset($_SESSION["sicherheitsstufe"]) and $_SESSION["sicherheitsstufe"] == "3") { 
	$db = mysqli_connect("localhost", "teststelleuser", "b25GLNKh8EmNolxH", "CDCC");
	
	if (isset($_POST['anzahl']) and $_POST['anzahl']=='anzahl'){    	
			$erg = $db->prepare("SELECT COUNT(*) AS 'Anzahl' FROM test WHERE ergebnis = '1'");
			$erg->execute();
			$result=$erg->get_result();
			echo "Kranke insgesamt: ";
            echo $result->fetch_object()->Anzahl;		
						
	} else if (isset($_POST['plz_check']) and $_POST['plz_check']=='plz_check'){
			$plz = "0";
			if (isset($_POST['plz'])) {
				$plz = trim($_POST['plz']);
			}
			if(intval ($plz) >= 10000 and intval($plz) <= 99999){
				$erg = $db->prepare("SELECT COUNT(*) AS 'Anzahl' FROM test JOIN patient ON patient.id = test.patientid WHERE patient.plz = ? AND test.ergebnis='1'");
				$erg->bind_param('s',$plz);
				$erg->execute();
				$result=$erg->get_result();
				echo $result->fetch_object()->Anzahl;
			}
			else{
				echo "Bitte legitime PLZ eingeben";
			}
			
	} else {
		echo "Nichts ausgewählt.";
	}
	?> 
	<br />
	<br />
	<br />
	<form action="" method="post">
	<input type="hidden" name="anzahl" value="anzahl">
    <input type="submit" value="Anzahl Fälle">
	</form>
	<br>
	<br>
	<br>
	<form action="" method="post">
	<input type="hidden" name="plz_check" value="plz_check">
	<input type="text" name="plz" placeholder="PLZ">
    <input type="submit" value="Anzahl Kranker in diesem Gebiet">
	</form>
	<?php
	}
	?>
</body>
</html>