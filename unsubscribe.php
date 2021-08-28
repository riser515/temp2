<?php  
session_start();
// include_once('db_con.php');

// Database connection info.
$DATABASE_HOST = '127.0.0.1:3307';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';

global $con;
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if(@$_POST['submit'] == 1){
  // $stmt = $con->prepare('update accounts set unsubscribe = ? where id = ?');
  // $stmt->bind_param('ii', $_POST['unsubscribe'], $_SESSION['id']);
  $stmt = $con->prepare('delete from accounts where id = ?');
  $stmt->bind_param('i', $_SESSION['id']);
  $stmt->execute();
  // Store the result so we can check if the account exists in the database.
  $stmt->store_result();
  header("Location: acknowledgement.html");
}
else{
  header("Location: home.php");
}
?>   
