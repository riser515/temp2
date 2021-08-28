<?php  
session_start();
include_once('db_con.php');

if ($account['activation_code'] != 'activated') {
	header("Location: index.html");
}

if(@$_POST['submit']){
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

<!-- <html>  
<center>  
<form method="post">  
<p>Do you really want to unsubscribe?</p>
<input type="radio" name="unsubscribe" value=1/>Unsubscribe
<input type="radio" name="unsubscribe" value=0/>No
<input type="submit" name="submit" value="Submit"/>
</form>  
</center>
</body>  
</html>   -->