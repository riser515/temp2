<?php
// session_start();
include_once('db_con.php');
// include_once('authenticate.php');

// // Database connection info.
// $DATABASE_HOST = '127.0.0.1:3307';
// $DATABASE_USER = 'root';
// $DATABASE_PASS = '';
// $DATABASE_NAME = 'phplogin';

// global $con;
// $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

function newComic($to_email){
        $url = "https://c.xkcd.com/random/comic/";
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // Must be set to true so that PHP follows any "Location:" header.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $a will contain all headers.
        $a = curl_exec($ch);
        // Returns the last effective URL.
        $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        
        $data = file_get_contents($url.'info.0.json'); // put the contents of the file into a variable
        $characters = json_decode($data, true);
        
        $imgTitle = $characters['title'];
        $imgAlt = $characters['alt'];
        // $imgLink = $characters->{'img'} . '<br>';
        $imgLink = $characters['img'];

        $imgFile = file_get_contents($imgLink);
        $imgFileName = explode("/", $imgLink);
        $imgFileName = $imgFileName[count($imgFileName) - 1];  

        $extension = explode(".", $imgFileName);
        $extension = $extension[1];

        $from_name = "KomixDose by Khushi Makhecha";
        $from_mail = "makhechakhushi@gmail.com";
        // $mail_to = "sparkler.star001@gmail.com";
        // $mail_to = "<?php $_POST['email']";
        $subject = "Your Latest XKCD Comic Dose";
        $message = '
        <html>
        <head>
        <title>KomixDose</title>
        </head>
        <body> 
            <h1>'.$imgTitle.'</h1>
            <img src='.$imgLink.' alt='.$imgAlt.'<br>
            <br><a href="https://komixdose.herokuapp.com/unsubscribe.php">Unsubscribe KomixDose?</a>
        </body>
        </html>';

        $content = chunk_split(base64_encode($imgFile));
        // A random hash for sending mixed content.
        $uid = md5(uniqid(time()));
        $eol = PHP_EOL;

        $headers = "From: ".$from_name." <".$from_mail.">".$eol;
        $headers  = 'MIME-Version: 1.0'.$eol;
        $headers .= "Content-Type: multipart/mixed; boundary=\"{$uid}\"".$eol;
        
        // Message.
        $body  = '--'.$uid.$eol;
        $body .= "Content-Type: text/html; charset=\"UTF-8\"".$eol;
        $body .= 'Content-Transfer-Encoding: 7bit'.$eol;
        $body .= $message.$eol;

        // Attachment.
        $body .= '--'.$uid.$eol;
        $body .= "Content-Type:{$extension}; name=\"{$imgFileName}\"".$eol;
        $body .= 'Content-Transfer-Encoding: base64'.$eol;
        $body .= "Content-disposition: attachment; filename=\"{$imgFileName}\"".$eol;
        $body .= $content.$eol;
        $body .= '--'.$uid.'--';

        $success = mail($to_email, $subject, $body, $headers);

        if ($success === false) {
            echo '<h3>Failure</h3>
            <p>Failed to send email to '.$to_email.'</p>';
        } else {
            echo '<p>Your email has been sent to '.$to_email.' successfully.</p>';
        }
    }

// $to_email = $argv[0];
newComic($to_email);
?>