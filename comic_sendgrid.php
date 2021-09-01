<?php
include_once 'db_con.php';
require_once 'config.php';
require 'vendor/autoload.php'; 

function newComic(){
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
        // echo $imgFile;
        $imgFileName = explode("/", $imgLink);
        // echo $imgFileName;
        $imgFileName = $imgFileName[count($imgFileName) - 1];  
        echo "imgFileName: " . $imgFileName;
        
        $extension = explode(".", $imgFileName);
        $extension = $extension[1];
        
        // $file_encoded = base64_encode(file_get_contents($imgFileName));
        // $content = chunk_split(base64_encode($imgFile));

        global $con;
        $query = "SELECT * FROM accounts"; 
        $result = mysqli_query ($con, $query) or die(mysqli_error($con));
        
        while ($row = mysqli_fetch_array($result)) { 
            // if (!$row) {
            //     printf("Error: %s\n", mysqli_error($con));
            //     exit();
            // }
            $mail_to= $row["email"];

        $email = new \SendGrid\Mail\Mail(); 
        $email->setFrom("makhechakhushi@gmail.com", "KomixDose");
        $email->setSubject("Your Latest XKCD Comic Dose");
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
        $email->addAttachment(base64_encode($imgFile), 'image/'.$extension, $imgFileName , 'inline' , $imgLink);        

        $sendgrid = new \SendGrid(SENDGRID_API_KEY);
        
        $response = $sendgrid->send($email);
        $statusCode = $response->statusCode() . "\n";
        
        if ($statusCode == 202) {
            // echo '<p>Your email has been sent to '.$email.' successfully.</p>';
            echo '<p>Your email has been sent successfully.</p>';
        } else {
            echo '<h3>Failure</h3>
            <p>Failed to send email</p>';
            // <p>Failed to send email to '.$email.'</p>';
        }
    }
    // mysqli_close($con);
    echo "Successfully executed newComic()";
}

newComic();
// while(true){
//     $starter = time();
//     echo $starter;
//     newComic();
//     $end = time();
//     echo $end;
//     echo "Next line of while loop";
//     sleep(120 - ($starter - $end));
// }
?>