<html>
  <link rel="stylesheet" type="text/css" href="css/parallax.css" />
  <link rel="stylesheet" type="text/css" href="css/nav.css" />
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
            <h2>Successfully Unsubscribed</h2>
            <p>
              You have successfully unsubscribed
              <span style="font-size: large">KomixDose</span>. It's so
              disappointing to see you go!
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
session_destroy();
?>