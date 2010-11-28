<?php
	session_start();
	require_once '../includes/class.php';
	session_defaults();
	header("Location: ../index.php");
?>