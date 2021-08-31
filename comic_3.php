<?php
require_once 'config.php';
require 'vendor/autoload.php'; 

$file_encoded = base64_encode(file_get_contents('cosmo.png'));

$email = new \SendGrid\Mail\Mail(); 
// $email = new SendGrid\Email();
$email->setFrom("makhechakhushi@gmail.com", "Example User");
$email->setSubject("Sending with SendGrid is Fun");
$email->addTo("sparkler.star001@gmail.com", "Example User");
$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
$email->addContent(
    "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
);
$email->addAttachment(
    $file_encoded,
    "image/png",
    "test.png",
    "attachment"
 );

// $attachment = 'cosmo.png';
// $content    = file_get_contents($attachment);
// $content    = chunk_split(base64_encode($content));

// $attachment = new SendGrid\Attachment();
// $attachment->setContent($content);
// $attachment->setType("application/pdf");
// $attachment->setType("png");
// $attachment->setFilename("RenamedFile.png");
// $attachment->setDisposition("attachment");
// $email->addAttachment($attachment);

$sendgrid = new \SendGrid(SENDGRID_API_KEY);

try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: '. $e->getMessage() ."\n";
}
?>