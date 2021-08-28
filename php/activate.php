<?php
include_once('/php/db_con.php');

if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	$msg = 'Failed to connect to MySQL: ' . mysqli_connect_error();
}
// First we check if the email and code exists...
if (isset($_POST['code'])) {
	if ($stmt = $con->prepare('SELECT * FROM accounts WHERE email = ? AND activation_code = ?')) {
		$stmt->bind_param('ss', $_POST['email'], $_POST['code']);
		$stmt->execute();
		// Store the result so we can check if the account exists in the database.
		$stmt->store_result();

		if ($stmt->num_rows > 0) {
			// Account exists with the requested email and code.
			if ($stmt = $con->prepare('UPDATE accounts SET activation_code = ? WHERE email = ? AND activation_code = ?')) {
				// Set the new activation code to 'activated', to check if the user has activated his account.
				$newcode = 'activated';
				$stmt->bind_param('sss', $newcode, $_POST['email'], $_POST['code']);
				$stmt->execute();
				$msg = 'Your account is now activated! You can now login!';
			}
		} else {
			$msg = 'The account is already activated or doesn\'t exist!';
		}
	}
}
?>

<html>
  <head>
    <link rel="stylesheet" type="text/css" href="/css/parallax.css" />
  </head>
  <body>
    <div class="container">
      <div class="front side">
        <div class="content one">
          <h1>KomixDose</h1>
          <p><?php echo $msg; ?></p>
          <p><em>For more details about <span>KomixDose</span>, hover anywhere on this card.</em></p>
        </div>
      </div>
      <div class="back side">
        <div class="content two">
          <p>
            "Subscribe to KomixDose to receive hilarious comics every 5 minutes,
            in your mail box!"
          </p>
          <p>
            "We provide free random comics from the well-known webcomic
            <a href="https://c.xkcd.com/random/comic/">XKCD Comics</a>."
          </p>
        </div>
      </div>
    </div>
  </body>
</html>