<?php
//fetch all data from the database
include "../includes/config.inc.php";
include "../includes/db_mysql.inc.php";
session_start();
if($_SESSION['admin_name']=="" || $_SESSION['admin_name']!='admin'){
 header('Location:login.php');
}
$admin_name=$_SESSION['admin_name'];
$rs=new DB_Sql;
$sql="SELECT * FROM users";
$rs->query($sql);
$rs->num_rows();


$data=array(array());
$num_data=0;

while($rs->next_record()){
 $data[$num_data]['user_id']=$rs->f('user_id');
 $data[$num_data]['user_nick']=$rs->f('user_nick');
 $data[$num_data]['user_email']=$rs->f('user_email');
 $data[$num_data]['user_description']=$rs->f('user_description');
 $num_data++;
}
include '../skin/admin/ap_header.tpl.php';
include '../skin/admin/ap_buser.tpl.php';
include '../skin/admin/ap_footer.tpl.php';
?>