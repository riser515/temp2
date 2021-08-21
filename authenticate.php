<?php
session_start();
include_once('db_con.php');

// If there is an error with the connection, stop the script and display the error.
if ( mysqli_connect_errno() ) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Could not get the data from the form, that should have been sent by the user.
if ( !isset($_POST['email'], $_POST['password']) ) {
	exit(`<h2>Please fill both the email and password fields!</h2>`);
}

// Preparing the SQL statements to prevent SQL injection.
if ($stmt = $con->prepare('SELECT id, username, password FROM accounts WHERE email = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc)
	$stmt->bind_param('s', $_POST['email']);
	$stmt->execute();
	$stmt->store_result();

    if ($stmt->num_rows == 1) {
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
            echo "<p style='color:red;'>Incorrect email and/or password!</p>";
        }
    } else {
        // Incorrect email
        echo 'Incorrect email address!';
    }
	$stmt->close();
}
?>