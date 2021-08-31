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


if(!empty($_SESSION["pass_manager_user"])){
	unset($_SESSION["admin"]);
	unset($_SESSION["pass_manager_user"]);
}
echo '<script>window.location.href="admin_main.php";</script>';

 ?>
