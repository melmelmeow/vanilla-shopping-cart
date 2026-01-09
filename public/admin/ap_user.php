<?php
include "../includes/config.inc.php";
include "../includes/db_mysql.inc.php";
//fetch posted data
$errors=array();
$pid=isset($_POST['pid']) ? $_POST['pid'] : $_GET['pid'] ;
$uid=isset($_POST['uid']) ? $_POST['uid'] : $_GET['uid'] ;
if(isset($err)){
$errors=explode(',',$err);
$num_errors=$_GET['err_count'];
$error_count=$num_errors;
$error_count.=$num_errors > 1 ? ' errors found' : ' error found';
$form_values=array(
'user_nick'=>$_POST['user_nick'],
'user_email'=>$_POST['user_email'],
'user_firstname'=>$_POST['user_firstname'],
'user_mi'=>$_POST['user_mi'],
'user_lastname'=>$_POST['user_lastname'],
'user_pwd'=>$_POST['user_pwd'],
'user_description'=>$_POST['user_description']
);

}

if( isset($pid) ){
 if($pid > 1){

 $rs=new DB_Sql;
 $sql="SELECT * FROM users WHERE user_id=".$uid;
 $rs->query($sql);
 $rs->next_record();
 $form_values=array(
  'user_nick'=>$rs->f('user_nick'),
  'user_email'=>$rs->f('user_email'),
  'user_firstname'=>$rs->f('user_firstname'),
  'user_mi'=>$rs->f('user_mi'),
  'user_lastname'=>$rs->f('user_lastname'),
  'user_description'=>$rs->f('user_description')
 );
 }
}


include '../skin/admin/ap_header.tpl.php';
include '../skin/admin/ap_user.tpl.php';
include '../skin/admin/ap_footer.tpl.php';

?>
