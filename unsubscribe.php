<?php  
session_start();

$DATABASE_HOST = '127.0.0.1:3307';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if(@$_POST['submit']){
    $stmt = $con->prepare('update accounts set unsubscribe = ? where id = ?');
    $stmt->bind_param('ii', $_POST['unsubscribe'], $_SESSION['id']);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();
}
?>   
<html>  
<center>  
<form method="post">  
<p>Do you really want to unsubscribe?</p>
<input type="radio" name="unsubscribe" value=1/>Unsubscribe
<input type="radio" name="unsubscribe" value=0/>No
<input type="submit" name="submit" value="Submit"/>
</form>  
</center>
</body>  
</html>  
