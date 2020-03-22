<?php
    session_start();

    if(!isset($_SESSION['testID']) or !isset($_GET['token'])) {
        die();
    }

    $id = $_SESSION['testID'];
    $token = $_GET['token'];

    $db = mysqli_connect("localhost", "patientlogin", "ydnHzWPvOJPWPCjz", "cdcc");
    $query = "SELECT hash FROM sso WHERE testID = ?";

    $sql = $db->prepare($query);
    $sql->bind_param('i',$id);
    $sql->execute();

    $result = $sql->get_result();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $hash = $row['hash'];
        }
    } else {
        die();
    }

    $validation = password_verify($token, $hash);

    if($validation) {
        $query = "DELETE FROM sso WHERE testID = '".$id."'";
        $result = $db->query($query);
        $_SESSION["val"] = $id;
        echo "true";
    } else {
        echo "false";
    }


?>