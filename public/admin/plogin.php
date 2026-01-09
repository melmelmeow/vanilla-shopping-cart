<?php
session_start();
include '../includes/config.inc.php';
include_once '../includes/database.inc.php';

if($_POST['admin_name']==""){
 header('Location:login.php?err=3');
}else{
 $_SESSION['admin_name']=$_POST['admin_name'];
 $admin_name=$_POST['admin_name'];
 $user_pwd=$_POST['user_pwd'];
}


$rs=new DB_RS;
$sql="SELECT * FROM settings where var='admin_login'";
$rs->query($sql);
$rs->next_record();
$u=$rs->f('value');
$sql="SELECT * FROM settings where var='admin_pwd'";
$rs->query($sql);
$rs->next_record();
$pwd=$rs->f('value');

if($admin_name!=$u){
 header('Location:login.php?err=4');
}


if($admin_name==$u && md5($user_pwd)==$pwd){
 $admin_name=$_SESSION['admin_name'];
 header("Location: index.php");
}else{
 header("Location: login.php?err=5&user=".$admin_name);
}


?>
