<?php
include "../includes/config.inc.php";
include "../includes/db_mysql.inc.php";
include "../includes/functions.inc.php";


//Verify variables
//List down all posted variables
$pid=isset($_POST['pid']) ? $_POST['pid'] : $_GET['pid'] ;
$uid=isset($_POST['uid']) ? $_POST['uid'] : $_GET['uid'] ;
$password1=$_POST['password1'];
$password2=$_POST['password2'];

$error=array();
$error_count=0;

if($password1 == $password2){
 $user_pwd=$password1;
}else{
 array_push($error,12);
 $error_count++;
}

$posted_values=array(
'user_nick'=>$_POST['user_nick'],
'user_email'=>$_POST['user_email'],
'user_firstname'=>$_POST['user_firstname'],
'user_mi'=>$_POST['user_mi'],
'user_lastname'=>$_POST['user_lastname'],
'user_description'=>$_POST['user_description'],
'user_pwd'=>md5($user_pwd)
);


$rs=new DB_Sql;


if(empty($user_nick)){
 array_push($error,6);
 $error_count++;
}

if(empty($user_email)){
 array_push($error,7);
 $error_count++;
}

if(empty($user_firstname)){
 array_push($error,8);
 $error_count++;
}
if(empty($user_mi)){
 array_push($error,9);
 $error_count++;
}
if(empty($user_lastname)){
 array_push($error,10);
 $error_count++;
}

if( empty($password1) && !empty($password2) ){
 array_push($error,11);
 $error_count++;
}

if( empty($password2) && !empty($password1) ){
 array_push($error,11);
 $error_count++;
}

if( empty($password1) && empty($password2) ){
 if($pid==1){
   array_push($error,11);
   $error_count++;
 }
}

if($error_count!=0){
 header( "Location:ap_user.php?pid=".$pid."&uid=".$uid."&err=".arr_build_query($error)."&".ret_query_val($posted_values)."&err_count=".$error_count );
}else{
 switch($pid){
  case 1:
   $rs->insert('users',$posted_values);
   $status='Created';
  break;
  
  case 2:
   if( empty($password1) && empty($password2) ){
    array_pop($posted_values);
   }
   $rs->update('users',$posted_values,'user_id='.$uid);
   $status='Modified';
  break;
 
  case 3:
   $rs->delete('users','user_id='.$uid);
   $status='Removed';
  break;

 default:
 header("Location:404.html");
 break;
 }
}

include '../skin/admin/ap_header.tpl.php';
include '../skin/admin/ap_puser.tpl.php';
include '../skin/admin/ap_footer.tpl.php';
?>