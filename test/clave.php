<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Genera Claves</title>
</head>

<body>

<?php 
if (isset($_POST['user']) AND isset($_POST['pass'])) {
	$clave = hash('sha512', $_POST['user'].$_POST['pass'].'1s3a3l7t');
	echo $clave;

} else {
?>
<form id="form1" name="form1" method="post" action="clave.php">
  <p>Nombre de Usuario:
    <input type="text" name="user" id="user" />
    <br />
    Password:
    <input type="password" name="pass" id="pass" />
  </p>
  <p>
    <input type="submit" name="bdbutton" id="bdbutton" value="Genera">
  </p>
</form>
<?php }?>
</body>
</html>
