<?php  
session_start();
include_once('db_con.php');

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
