<?php
session_start();

require_once '../includes/class.php';
$user = $_POST['user'];
$user = @strip_tags($user);
$user = @stripslashes($user); 
$pass = $_POST['pass'];

$date = date('Y-m-d');
$usuario = new Usuario();
$usuario->_checkLogin($user, $pass);
header("Location: ../index.php");
?>
