<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit;
}

include_once('db_con.php');

if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit;
} 

if(@$_POST['continue'] == 1){
  // We don't have the password or email info stored in sessions so instead we can get the results from the database.
  $stmt = $con->prepare('SELECT password, email FROM accounts WHERE id = ?');
  // In this case we can use the account ID to get the account info.
  $stmt->bind_param('i', $_SESSION['id']);
  $stmt->execute();
  $stmt->bind_result($password, $email);
  $stmt->fetch();
  $stmt->close();
}
else{
  header("Location: home.php");
}
?>

<html>
  <head>
    <link rel="stylesheet" type="text/css" href="css/parallax.css" />
    <link rel="stylesheet" type="text/css" href="css/nav.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Londrina+Solid&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <nav>
      <ul>
        <li>
          <a href="home.php">Home</a>
        </li>
        <li>
          <a href="profile.html">Profile</a>
        </li>
        <li>
          <a href="unsubscribe.html">Unsubscribe</a>
        </li>
        <li>
          <a href="logout.php">Logout</a>
        </li>
      </ul>
    </nav>
    <div class="body__container">
      <div class="container">
        <div class="front side">
          <div class="content one">
            <h1>KomixDose</h1>
            <p>Your account details are as below:</p>
            <p class="details">
              <span>Username:</span>
              <?=$_SESSION['name']?>
            </p>

            <p class="details">
              <span>Password:</span>
              Your encrypted password is
              <?=strlen($password)?>
              characters long.
            </p>

            <p class="details">
              <span>Email:</span>
              <?=$email?>
            </p>
          </div>
        </div>
        <div class="back side">
          <div class="content two">
            <p>
              "Subscribe to KomixDose to receive hilarious comics every 5
              minutes, in your mail box!"
            </p>
            <p>
              "We provide free random comics from the well-known webcomic
              <a href="https://c.xkcd.com/random/comic/">XKCD Comics</a>."
            </p>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
