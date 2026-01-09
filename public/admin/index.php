<?php
session_start();
if($_SESSION['admin_name']=="" || $_SESSION['admin_name']!='admin'){
 header('Location:login.php');
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Admin Panel</title>
</head>
<frameset cols="160,*"  framespacing="0" border="0" frameborder="0" frameborder="no" border="0">
	<frame src="ap_nav.php" name="nav" scrolling="yes" frameborder="0" marginwidth="0" marginheight="0" border="no" noresize="noresize" />
	<frame src="ap_info.php" name="main" scrolling="auto" frameborder="0" marginwidth="10" marginheight="10" border="no" noresize="noresize" />
</frameset>
</html>
