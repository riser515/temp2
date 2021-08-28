<?php
// Starting session
session_start();

// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
?>
<!-- 
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>KomixDose</title>
		<link href="color_card.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>KomixDose</h1>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>KomixDose</h2>
			<p>Welcome back, <?=$_SESSION['name']?>!</p>
			<p>Thank you for subscribing to <strong>KomixDose</strong>!</p> 
			<p>We deliver random interesting comics to your inbox, every 5 minutes.</p>
		</div>
			<a href="http://localhost:8080/phplogin/unsubscribe.php">Unsubscribe KomixDose</a>
	</body>
</html> -->

<html>
  <head>
    <link rel="stylesheet" type="text/css" href="parallax.css" />
    <link rel="stylesheet" type="text/css" href="nav.css" />
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
            <p>
              Hey <span><?=$_SESSION['name']?></span>, open your email inbox and
              have fun!
            </p>
            <p>
              <em
                >For more details about <span>KomixDose</span>, hover anywhere
                on this card.</em
              >
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

<?php 
	include_once('comic_mail.php');
?>