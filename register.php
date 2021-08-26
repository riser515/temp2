<?php
include_once('db_con.php');

if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Now we check if the data was submitted.
if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
	// Could not get the data that should have been sent.
	exit('Please complete the registration form!');
}

// Make sure the submitted registration values are not empty.
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
	// One or more values are empty.
	exit('Please complete the registration form');
}

// Email validation
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	exit('Email is not valid!');
}

// Invalid characters validation
if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
    exit('Username is not valid!');
}

// Character length check
if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
	exit('Password must be between 5 and 20 characters long!');
}

// If the account with that email exists.
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE email = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
	$stmt->bind_param('s', $_POST['email']);
	$stmt->execute();
	$stmt->store_result();
	// Store the result so we can check if the account exists in the database.
	if ($stmt->num_rows > 0) {
		// Email already exists
		echo 'This email is already registered with KomixDose!';
	} else {
        // Email doesn't exist, insert new account
        if ($stmt = $con->prepare('INSERT INTO accounts (username, password, email, activation_code) VALUES (?, ?, ?, ?)')) {
            // Hash the password and use password_verify when a user logs in.
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $uniqid = random_int(100000, 999999);
            $stmt->bind_param('ssss', $_POST['username'], $password, $_POST['email'], $uniqid);
            $stmt->execute();
            
			$from_name = "KomixDose by Khushi Makhecha";
			$from_mail = "makhechakhushi@gmail.com";
            $subject = 'Account Activation Required';
            $message = '
			<html>
			<body>
			<p>Please enter this code to activate your account: ' . $uniqid. ' </p>
			</body>
			</html>
			';
			
			// A random hash for sending mixed content.
			$uid = md5(uniqid(time()));
			$eol = PHP_EOL;

			$headers = "From: ".$from_name." <".$from_mail.">".$eol;
			$headers .= 'MIME-Version: 1.0'.$eol;
			$headers .= "Content-Type: multipart/mixed; boundary=\"{$uid}\"".$eol;
			
			// Message.
			$body  = '--'.$uid.$eol;
			$body .= "Content-Type: text/html; charset=\"UTF-8\"".$eol;
			$body .= 'Content-Transfer-Encoding: 7bit'.$eol;
			$body .= $message.$eol;

            $success = mail($_POST['email'], $subject, $body, $headers);

			if ($success === false) {
				echo '<h3>Failure</h3>;
				<p>Failed to send email to '.$_POST['email'].'</p>';
			} else {
				echo '<p>Your email has been sent to '.$_POST['email'].' successfully.</p>';
			}
        } else {
            // Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
            echo 'Technical issue encountered!';
        }
	}
	$stmt->close();
} else {
	// Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
	echo 'Could not prepare statement!';
}
$con->close();
?>

<html>
<head>
<title>KomixDose</title>
</head>
<body>
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
placeholder="Enter your code"
id="code"
required
/>
<input type="submit" value="Verify" />
</body>
</html>