<?php
include "../includes/config.inc.php";
include "../includes/db_mysql.inc.php";
include "../includes/category.inc.php";
require "../includes/functions.inc.php";
session_start();
if($_SESSION['admin_name']=="" || $_SESSION['admin_name']!='admin'){
 header('Location:login.php');
}
$admin_name=$_SESSION['admin_name'];
//Verify variables
//List down all posted variables
$rsC=new Category;
$pid=isset($_POST['pid']) ? $_POST['pid'] : $_GET['pid'] ;
$iid=isset($_POST['iid']) ? $_POST['iid'] : $_GET['iid'] ;


$userfile1 = $HTTP_POST_FILES['package_image']['tmp_name'];
$userfile_name1 = $HTTP_POST_FILES['package_image']['name'];
$userfile_size1 = $HTTP_POST_FILES['package_image']['size'];
$userfile_type1 = $HTTP_POST_FILES['package_image']['type'];
$userfile_error1 = $HTTP_POST_FILES['package_image']['error'];
$location="/package_images";

$posted_values=array(
'package_name'=>$_POST['package_name'],
'category_id'=>$_POST['category_id'],
'package_short_description'=>$_POST['package_short_description'],
'package_full_description'=>$_POST['package_full_description'],
'package_price'=>$_POST['package_price'],
'package_maturity_length'=>$_POST['package_maturity_length'],
'package_maturity_length_years'=>$_POST['package_maturity_length_years'],
'package_interest'=>$_POST['package_interest'],
'package_limit_per_month'=>$_POST['package_limit_per_month'],
'package_image'=>$userfile_name1
);


$error=array();
$error_count=0;
$rs=new DB_Sql;


if(empty($posted_values['package_name'])){
 array_push($error,15);
 $error_count++;
}

if(empty($posted_values['package_short_description'])){
 array_push($error,13);
 $error_count++;
}

if(empty($posted_values['package_full_description'])){
 array_push($error,21);
 $error_count++;
}

if(empty($posted_values['package_price'])){
 array_push($error,22);
 $error_count++;
}
if(empty($posted_values['package_limit_per_month'])){
 array_push($error,23);
 $error_count++;
}

if($pid ==1){
 if(empty($posted_values['package_image'])){
  array_push($error,14);
  $error_count++;
 }
}

if($error_count!=0){
 header( "Location:ap_package.php?pid=".$pid."&iid=".$iid."&err=".arr_build_query($error)."&".ret_query_val($posted_values)."&err_count=".$error_count );
}else{
switch($pid){
 case 1:
  if( !empty($posted_values['package_image']) ){
   @upload_image_file($userfile1,$userfile_name1,$userfile_size1,$userfile_type1,$userfile_error1,$location);
   $posted_values['user_id']=0;
   $posted_values['insertedon']=date("Y-m-d H:i:s",mktime());
  }
  $rs->insert('packages',$posted_values);
  $status='Created';
 break;
  
 case 2:
  if( empty($posted_values['package_image']) ){
   array_pop($posted_values);
  }else{
   @upload_image_file($userfile1,$userfile_name1,$userfile_size1,$userfile_type1,$userfile_error1,$location);
  } 
  $rs->update('packages',$posted_values,'package_id='.$iid);
  $sql="SELECT * FROM packages WHERE package_id=".$iid;
  $rs->query($sql);
  $rs->next_record();
  $posted_values['package_image']=$rs->f('package_image');
  $status='Modified';
 break;
 
 case 3:
  $rs->delete('packages','package_id='.$iid);
  $status='Removed';
 break;

 default:
 header("Location:404.html");
 break;
 }
}
$posted_values['category_name']=$posted_values['category_id']==0 ? '/' : $rsC->getTreeAscName2($posted_values['category_id']);

include '../skin/admin/ap_header.tpl.php';
include '../skin/admin/ap_ppackage.tpl.php';
include '../skin/admin/ap_footer.tpl.php';
?>