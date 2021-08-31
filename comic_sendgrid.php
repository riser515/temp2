<?php

// Pending
include_once 'db_con.php';
require_once 'config.php';
require 'vendor/autoload.php'; 

function newComic(){
        // $mail_to = $_POST['email'];
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
        echo $imgLink;
        
        $imgFile = file_get_contents($imgLink);
        // echo $imgFile;
        $imgFileName = explode("/", $imgLink);
        // echo $imgFileName;
        $imgFileName = $imgFileName[count($imgFileName) - 1];  
        echo $imgFileName;
        
        $extension = explode(".", $imgFileName);
        // echo $extension;
        $extension = $extension[1];
        echo $extension;
        
        $file_encoded = base64_encode(file_get_contents($imgFileName));
        
        global $con;
        $query = "SELECT * FROM accounts"; 
        $result = mysqli_query ($con, $query);
        
        while ($row = mysqli_fetch_array($result)) { 
            $mail_to= $row["email"];

        $email = new \SendGrid\Mail\Mail(); 
        $email->setFrom("makhechakhushi@gmail.com", "KomixDose");
        $email->setSubject("Your Latest XKCD Comic Dose");
        // $email->addContent("text/plain", "and easy to do anywhere, even with PHP");
        $email->addTo($mail_to, "KomixDose Subscriber");
        $email->addContent(
            "text/html", '
            <html>
            <head>
            <title>KomixDose</title>
            </head>
            <body> 
            <h1>'.$imgTitle.'</h1>
            <img src='.$imgLink.' alt='.$imgAlt.'<br>
            <br><a href="https://komixdose.herokuapp.com/unsubscribe.php">Unsubscribe KomixDose?</a>
            </body>
            </html>'
        );
        $email->addAttachment(
            $file_encoded,
            $extension,
            $imgFileName,
            "attachment"
        );
        
        $sendgrid = new \SendGrid(SENDGRID_API_KEY);
        

        // $success = mail($email, $subject, $body, $headers);
        // mail($email, $subject, $body, $headers);

        try {
            $response = $sendgrid->send($email);
            print $response->statusCode() . "\n";
            print_r($response->headers());
            print $response->body() . "\n";
        } catch (Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
        }
    }
        // $mail_to = "sparkler.star001@gmail.com";
        // $mail_to = "<?php $_POST['email']";
        // $subject = "Your Latest XKCD Comic Dose";
        // $message = '
        // <html>
        // <head>
        // <title>KomixDose</title>
        // </head>
        // <body> 
        //     <h1>'.$imgTitle.'</h1>
        //     <img src='.$imgLink.' alt='.$imgAlt.'<br>
        //     <br><a href="https://komixdose.herokuapp.com/unsubscribe.php">Unsubscribe KomixDose?</a>
        // </body>
        // </html>';

    //     $content = chunk_split(base64_encode($imgFile));
    //     // A random hash for sending mixed content.
    //     $uid = md5(uniqid(time()));
    //     $eol = PHP_EOL;

    //     $headers = "From: ".$from_name." <".$from_mail.">".$eol;
    //     $headers  = 'MIME-Version: 1.0'.$eol;
    //     $headers .= "Content-Type: multipart/mixed; boundary=\"{$uid}\"".$eol;
        
    //     // Message.
    //     $body  = '--'.$uid.$eol;
    //     $body .= "Content-Type: text/html; charset=\"UTF-8\"".$eol;
    //     $body .= 'Content-Transfer-Encoding: 7bit'.$eol;
    //     $body .= $message.$eol;

    //     // Attachment.
    //     $body .= '--'.$uid.$eol;
    //     $body .= "Content-Type:{$extension}; name=\"{$imgFileName}\"".$eol;
    //     $body .= 'Content-Transfer-Encoding: base64'.$eol;
    //     $body .= "Content-disposition: attachment; filename=\"{$imgFileName}\"".$eol;
    //     $body .= $content.$eol;
    //     $body .= '--'.$uid.'--';

    //     global $con;
    //     $query = "SELECT * FROM accounts"; 
    //     $result = mysqli_query ($con, $query);
        
    //     while ($row = mysqli_fetch_array($result)) { 
    //         $email= $row["email"]; 

    //         $success = mail($email, $subject, $body, $headers);
    //         // mail($email, $subject, $body, $headers);

    //         if ($success === false) {
    //             echo '<h3>Failure</h3>
    //             <p>Failed to send email to '.$email.'</p>';
    //         } else {
    //             echo '<p>Your email has been sent to '.$email.' successfully.</p>';
    //         }
    // }
}

// $to_email = $argv[0];
newComic();
mysqli_close($con);
?>


<!-- // $file_encoded = base64_encode(file_get_contents('cosmo.png'));

// $email = new \SendGrid\Mail\Mail(); 
// $email->setFrom("makhechakhushi@gmail.com", "Example User");
// $email->setSubject("Sending with SendGrid is Fun");
// $email->addTo("sparkler.star001@gmail.com", "Example User");
// // $email->addContent("text/plain", "and easy to do anywhere, even with PHP");
// $email->addContent(
//     "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
// );
// $email->addAttachment(
//     $file_encoded,
//     "image/png",
//     "test.png",
//     "attachment"
//  );

// $sendgrid = new \SendGrid(SENDGRID_API_KEY);

// try {
//     $response = $sendgrid->send($email);
//     print $response->statusCode() . "\n";
//     print_r($response->headers());
//     print $response->body() . "\n";
// } catch (Exception $e) {
//     echo 'Caught exception: '. $e->getMessage() ."\n"; -->
<!-- // } -->
