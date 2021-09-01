<?php  
session_start();
include_once('db_con.php');

// if ($account['activation_code'] != 'activated') {
// 	header("Location: index.php");
// }

if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit;
}

if(@$_POST['continue'] == 1){
  // $stmt = $con->prepare('update accounts set unsubscribe = ? where id = ?');
  // $stmt->bind_param('ii', $_POST['unsubscribe'], $_SESSION['id']);
  $stmt = $con->prepare('delete from accounts where id = ?');
  $stmt->bind_param('i', $_SESSION['id']);
  $stmt->execute();
  // Store the result so we can check if the account exists in the database.
  $stmt->store_result();
  $_SESSION['loggedin'] = TRUE;
  header("Location: acknowledgement.php");
}
else{
  header("Location: home.php");
}
?>   
