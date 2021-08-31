<?php

  //Get Heroku ClearDB connection information
  $cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
  $cleardb_server = $cleardb_url["host"];
  $cleardb_username = $cleardb_url["user"];
  $cleardb_password = $cleardb_url["pass"];
  $cleardb_db = substr($cleardb_url["path"],1);
  $active_group = 'default';
  $query_builder = TRUE;

  // Connect to DB
  $con = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Login to KomixDose</title>
    <link
      rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"
    />
    <link href="css/style_login.css" rel="stylesheet" type="text/css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Londrina+Solid&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <div class="login">
      <h1>Login to <span class="komixdose">KomixDose</span></h1>
      <form action="authenticate.php" method="post">
        <label for="email">
          <i class="fas fa-user"></i>
        </label>
        <input
          type="text"
          name="email"
          placeholder="Email"
          id="email"
          required
        />
        <label for="password">
          <i class="fas fa-lock"></i>
        </label>
        <input
          type="password"
          name="password"
          placeholder="Password"
          id="password"
          required
        />
        <input type="submit" value="Login" />
      </form>
    </div>
    <a id="register__link" href="register.html">Don't have account?</a>
  </body>
</html>