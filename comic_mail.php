<?php
session_start();

$DATABASE_HOST = '127.0.0.1:3307';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

$unsubscribe_value = $con->prepare('select unsubscribe from accounts where id = ?');
$unsubscribe_value->bind_param('i', $_SESSION['id']);
$unsubscribe_value->execute();
// $unsubscribe_value->store_result();
$result = $unsubscribe_value->get_result();
$result = $result->fetch_array();
// $saved = $result['unsubscribe'];

function newComic(){
    $sourceURL="https://c.xkcd.com/random/comic/";
    $data=file_get_contents($sourceURL);

    if(preg_match('%(Permanent\slink\sto\sthis\scomic:\shttps://xkcd.com/)(\d+)%', $data, $match)){
        $saved = substr($match[0], 30);
        $to_be_added = '/info.0.json';
        $saved .= $to_be_added;

        $data = file_get_contents($saved); // put the contents of the file into a variable
        $characters = json_decode($data);
        
        $imgTitle = $characters->{'title'} . '<br>';
        $imgAlt = $characters->{'alt'} . '<br>';
        // $imgLink = $characters->{'img'} . '<br>';
        $imgLink = $characters->{'img'};

        $imgFile = file_get_contents($imgLink);
        $imgFileName = explode("/", $imgLink);
        $imgFileName = $imgFileName[count($imgFileName) - 1];  

        $extension = explode(".", $imgFileName);
        $extension = $extension[1];

        $from_name = "KomixDose by Khushi Makhecha";
        $from_mail = "makhechakhushi@gmail.com";
        $mail_to = "sparkler.star001@gmail.com";
        $subject = "Your Latest XKCD Comic Dose";
        $message = '
        <html>
        <head>
        <title>KomixDose</title>
        </head>
        <body> 
            <h1>'.$imgTitle.'</h1>
            <img src='.$imgLink.' alt='.$imgAlt.'<br>
            <a href="http://localhost:8080/phplogin/unsubscribe.php">Unsubscribe KomixDose?</a>
        </body>
        </html>';

        $content = chunk_split(base64_encode($imgFile));
        // A random hash will be necessary to send mixed content.
        $uid = md5(uniqid(time()));
        // Carriage return type (RFC).
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

        $success = mail($mail_to, $subject, $body, $headers);

        if ($success === false) {
            echo '<h3>Failure</h3>';
            echo '<p>Failed to send email to '.$to.'</p>';
        } else {
            echo '<p>Your email has been sent to '.$mail_to.' successfully.</p>';
        }
    }
}

newComic();
// function mailSender(){
//     global $saved;
//     if($saved === 0){
//         newComic(); 
//         // sleep(300);
//         // mailSender();   
//     }
//     else{
//         echo "The user has unsubscribed!";
//     }
// }

?>