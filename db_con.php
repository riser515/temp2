<?php

// Database connection info.
$DATABASE_HOST = '127.0.0.1:3307';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';

// $DATABASE_HOST = 'us-cdbr-east-04.cleardb.com';
// $DATABASE_USER = 'ba38f742a2c2bd';
// $DATABASE_PASS = '04807876';
// $DATABASE_NAME = 'heroku_86a9ce3be59f4af';

global $con;
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
?>