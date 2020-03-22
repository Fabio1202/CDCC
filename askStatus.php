 <?php 
            session_start();
            
            require 'aws/aws-autoloader.php';

            use Aws\Sns\SnsClient; 
            use Aws\Ses\SesClient;
            use Aws\Exception\AwsException;
        
        if(!isset($_GET['user']) or $_GET['user'] == "") {
            die();
        }

        $id = $_GET['user'];

        for ($i=0; $i < 9; $i++) { 
            if(substr($id, 0, 1) == "0") {
                $id = substr($id, 1);
            }
        }

        $db = mysqli_connect("localhost", "patientlogin", "ydnHzWPvOJPWPCjz", "cdcc");
        $query = "SELECT telefon, mail FROM patient JOIN test ON patient.id = test.patientid WHERE test.id = ?";

        $sql = $db->prepare($query);
        $sql->bind_param('i',$id);
        $sql->execute();

        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $sms = $row["telefon"];
                $mail = $row["mail"];
            }
        } else {
            die();
        }

        $auth = random_int(111111,999999);
        $date = date("Y.m.d");
        $hash = password_hash($auth,PASSWORD_DEFAULT);
        $message = 'Ihr Bestätigungscode für CDCC lautet: '.$auth;

        $query = "DELETE FROM sso WHERE testID='".$id."'";
        $result = $db->query($query);

        if(!$result) {
            echo "Error occurred! #1";
            die();
        }

        $query = "INSERT INTO sso (datum, testID, hash) VALUES ('".$date."','".$id."','".$hash."')";
        $result = $db->query($query);

        if(!$result) {
            echo "Error occurred! #2";
            die();
        }

            $SnSclient = new SnsClient([
                'profile' => 'default',
                'region' => 'us-east-1',
                'version' => '2010-03-31'
            ]);

            $SesClient = new SesClient([
                'profile' => 'default',
                'version' => '2010-12-01',
                'region'  => 'eu-central-1'
            ]);


        if($sms != "") {

            try {
                $result = $SnSclient->publish([
                    'Message' => $message,
                    'PhoneNumber' => $sms,
                ]);
                $_SESSION['testID'] = $id;
                echo "true";
            } catch (AwsException $e) {
                echo ($e->getMessage());
                // output error message if fails
                error_log($e->getMessage());
                die();
            } 
        } elseif ($mail != "") {
            $sender_email = 'Fabio@topspinners.net';
            $recipient_emails = [$mail];
            //$configuration_set = 'ConfigSet';
            $subject = 'Ihr Verifizierungscode für CDCC';
            $plaintext_body = 'Ihr Verifizierungscode lautet: '.$auth ;
            $html_body =  '<h1>Ihr Verifizierungscode</h1>'.
              '<p>Ihr Verifizierungscode für CDCC lautet: '.$auth.'</p>';
            $char_set = 'UTF-8';
            try {
                $result = $SesClient->sendEmail([
                    'Destination' => [
                        'ToAddresses' => $recipient_emails,
                    ],
                    'ReplyToAddresses' => [$sender_email],
                    'Source' => $sender_email,
                    'Message' => [
                    'Body' => [
                        'Html' => [
                            'Charset' => $char_set,
                            'Data' => $html_body,
                        ],
                        'Text' => [
                            'Charset' => $char_set,
                            'Data' => $plaintext_body,
                        ],
                    ],
                    'Subject' => [
                        'Charset' => $char_set,
                        'Data' => $subject,
                    ],
                    ],
                    // If you aren't using a configuration set, comment or delete the
                    // following line
                 
                ]);
                $messageId = $result['MessageId'];
                $_SESSION['testID'] = $id;
                echo("true");
            } catch (AwsException $e) {
                // output error message if fails
                echo $e->getMessage();
                echo("The email was not sent. Error message: ".$e->getAwsErrorMessage()."\n");
                echo "\n";
            }
        }


        // require 'aws/aws-autoloader.php';

        // use Aws\Sns\SnsClient; 
        // use Aws\Exception\AwsException;

        // $SnSclient = new SnsClient([
        //     'profile' => 'default',
        //     'region' => 'eu-central-1',
        //     'version' => '2010-03-31'
        // ]);

        // $message = 'This message is sent from a Amazon SNS code sample.';
        // $phone = '+4915204787333';

        // try {
        //     $result = $SnSclient->publish([
        //         'Message' => $message,
        //         'PhoneNumber' => $phone,
        //     ]);
        //     var_dump($result);
        // } catch (AwsException $e) {
        //     echo "test";
        //     // output error message if fails
        //     error_log($e->getMessage());
        // } 

        //echo getenv('HOME');
    ?>
