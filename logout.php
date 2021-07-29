<?php
session_start();
session_destroy();

// Redirecting to the login page:
header('Location: index.html');
?>