<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>退出登陆</title>
</head>
<body>
	
</body>

<?php 
//屏蔽错误
error_reporting(0);
session_start();


if (!empty($_SESSION["pass_user"])) {
	unset($_SESSION["user"]);
	unset($_SESSION["pass_user"]);
}
echo '<script>window.location.href="login.html";</script>';
 ?>
