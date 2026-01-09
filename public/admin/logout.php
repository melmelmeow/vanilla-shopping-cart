<?php
session_start();

session_unregister('user_name');
header("Location:login.php");
?>

