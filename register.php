<?php
include_once('db_con.php');

global $con;
if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	$msg = 'Failed to connect to MySQL: ' . mysqli_connect_error();
}

// Now we check if the data was submitted.
else if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
	// Could not get the data that should have been sent.
	$msg = 'Please complete the registration form!';
}

// Make sure the submitted registration values are not empty.
else if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
	// One or more values are empty.
	$msg = 'Please complete the registration form';
}

// Email validation
else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	$msg = 'Email is not valid!';
}

// Invalid characters validation
else if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
    $msg = 'Username is not valid!';
}

// Character length check
else if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 6) {
	$msg = 'Password must be 6 to 20 characters long!';
}

// If the account with that email exists.
else if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE email = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
	$stmt->bind_param('s', $_POST['email']);
	$stmt->execute();
	$stmt->store_result();
	// Store the result so we can check if the account exists in the database.
	if ($stmt->num_rows > 0) {
		// Email already exists
		$msg = 'This email is already registered with KomixDose!';
	} else {
        // Email doesn't exist, insert new account
        if ($stmt = $con->prepare('INSERT INTO accounts (username, password, email, activation_code) VALUES (?, ?, ?, ?)')) {
            // Hash the password and use password_verify when a user logs in.
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $uniqid = random_int(100000, 999999);
            $stmt->bind_param('ssss', $_POST['username'], $password, $_POST['email'], $uniqid);
            $stmt->execute();
            
            require_once 'config.php';
            require 'vendor/autoload.php'; 

            $email = new \SendGrid\Mail\Mail(); 
            // $email = new SendGrid\Email();
            $email->setFrom("makhechakhushi@gmail.com", "KomixDose by Khushi Makhecha");
            $email->setSubject("Account Activation Required");
            $email->addTo($_POST['email'], "Subscribed User");
            // $email->addContent("text/plain", "and easy to do anywhere, even with PHP");
            $email->addContent(
                "text/html", "
                <html>
                <body>
                <p>Please enter this code to activate your account: " . $uniqid. " </p>
                </body>
                </html>
                "
            );

            $sendgrid = new \SendGrid(SENDGRID_API_KEY);

            $response = $sendgrid->send($email);
            $statCode = $response->statusCode() . "\n";

            if($statCode == 202){
                $msg = '<p>Your registration otp has been sent to '.$_POST['email'].' successfully.</p>';
            }
            else{
                $msg = '<p>Failed to send email to '.$_POST['email'].'</p>';
            }
        } else {
            // Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
            $msg = 'Technical issue encountered!';
        }
	}
	$stmt->close();
} else {
	// Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
	$msg = 'Could not prepare statement!';
}
$con->close();
?>

<html>
  <head>
    <link rel="stylesheet" type="text/css" href="css/parallax.css" />
    <link rel="stylesheet" type="text/css" href="css/register_input.css" />
  </head>
  <body>
    <div class="body__container">
      <div class="container">
        <div class="front side">
          <div class="content one">
            <h1>KomixDose</h1>
            <p><?php echo $msg; ?></p>
            <p><em>Hover anywhere on this card and enter the otp received in your email.</em></p>
          </div>
        </div>
        <div class="back side">
          <div class="content two">
            <form action="activate.php" method="post" autocomplete="off">
            <input
              type="email"
              name="email"
              placeholder="Enter your registered email id"
              id="email"
              required
            />
            <input
              type="text"
              name="code"
              placeholder="Enter received code"
              id="code"
              required
            />
            <input id="ver__button" type="submit" value="Verify" />
            </form>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>