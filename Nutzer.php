<head>
	<title>Nutzer</title>
	<link rel="stylesheet" type="text/css" href="\css\style.css" />
</head>
<body>
	<h1>Nutzer</h1>
	<?php 
	$db = mysqli_connect("localhost", "teststelleuser", "b25GLNKh8EmNolxH", "CDCC");
	
	if (isset($_POST['aktion']) and $_POST['aktion']=='speichern') {
    $username = "";
    if (isset($_POST['username'])) {
        $username = trim($_POST['username']);
    }
	$passwort = "";
    if (isset($_POST['passwort'])) {
        $passwort = trim($_POST['passwort']);
    }
	
	$datum = date("Y-m-d");
	
	$erg = $db->query("SELECT zugriffsstufe FROM zugang WHERE name = '".$_POST['name']."' AND passwort = '".$_POST['pw']."'");
			if ($erg->num_rows == 1) {
				$zugriffsstufe = $erg->fetch_object();
				echo "Zugriff gewährt. Sicherheitstufe: ";
                echo $zugriffsstufe->zugriffsstufe;
	}
	?> 
	<form action="" method="post">
	<label>
	Username:
	</label>
	<input type="text" name="username" value="" id="username">
	<br>
	<label>
	Passwort:
	</label>
	<input type="text" name="passwort" value="" id="passwort">
	<br>
	<input type="hidden" name="aktion" value="speichern">
    <input type="submit" value="speichern">
	</form> 
	
	
	

</body>