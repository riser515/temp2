<?php
session_start();
include_once('db_con.php');

// If there is an error with the connection, stop the script and display the error.
if ( mysqli_connect_errno() ) {
  $msg = 'Failed to connect to MySQL: ' . mysqli_connect_error();
}

// Could not get the data from the form, that should have been sent by the user.
if ( !isset($_POST['email'], $_POST['password']) ) {
  $msg = `<h2>Please fill both the email and password fields!</h2>`;
}

// Preparing the SQL statements to prevent SQL injection.
if ($stmt = $con->prepare('SELECT id, username, password FROM accounts WHERE email = ?')) {
  // Bind parameters (s = string, i = int, b = blob, etc)
	$stmt->bind_param('s', $_POST['email']);
	$stmt->execute();
	$stmt->store_result();
  
  if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $username, $password);
    $stmt->fetch();
    // Account exists, now we verify the password.
    // Note: password_hash stores the hashed passwords.
    if (password_verify($_POST['password'], $password)) {
      // Verification success! User has logged-in!
      // Create sessions, so we know the user is logged in.
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $username;
            $_SESSION['id'] = $id;
            header('Location: home.php');
          } else {
            // Incorrect password
            $msg = "Incorrect password!</p>";
          }
        } else {
          // Incorrect email
          $msg = 'Incorrect email address!';
        }
        $stmt->close();
      }
      $to_email = $_POST['email'];
      shell_exec("php comic_mail.php $to_email 2>&1");
      ?>

<html>
  <head>
    <link rel="stylesheet" type="text/css" href="css/parallax.css" />
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

<?php
include('comic_mail.php');
?>