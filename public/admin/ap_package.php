<?php
include '../includes/config.inc.php';
include '../includes/db_mysql.inc.php';
include '../includes/category.inc.php';
session_start();
if($_SESSION['admin_name']=="" || $_SESSION['admin_name']!='admin'){
 header('Location:login.php');
}
$admin_name=$_SESSION['admin_name'];

$errors=array();
$rsCategory=new Category;

$select_contents=explode(";",$rsCategory->arrTreeName2());
array_pop($select_contents);

$iid=isset($_POST['iid']) ? $_POST['iid'] : $_GET['iid'];
$pid=isset($_POST['pid']) ? $_POST['pid'] : $_GET['pid'];


if(isset($err)){
 $errors=explode(',',$err);
 $num_errors=$_GET['err_count'];
 $error_count=$num_errors;
 $error_count.=$num_errors > 1 ? ' errors found' : ' error found';
 
 $form_values=array(
  'package_name'=>$_REQUEST['package_name'],
  'category_id'=>$_REQUEST['category_id'],
  'category_id'=>$_REQUEST['category_id'],
  'package_description'=>$_REQUEST['package_description'],
  'package_short_description'=>$_REQUEST['package_short_description'],
  'package_full_description'=>$_REQUEST['package_full_description'],
  'package_maturity_length'=>$_REQUEST['package_maturity_length'],
  'package_maturity_length'=>$_REQUEST['package_maturity_length_years'],
  'package_price'=>$_REQUEST['package_price'],
  'package_limit_per_month'=>$_REQUEST['package_limit_per_month']

 );
}

if( isset($pid) ){
 if($pid > 1){

 $rs=new DB_Sql;
 $sql="SELECT * FROM packages WHERE package_id=".$iid;
 $rs->query($sql);
 $rs->next_record();
 $form_values=array(
  'package_name'=>$rs->f('package_name'),
  'category_id'=>$rs->f('category_id'),
  'package_short_description'=>$rs->f('package_short_description'),
  'package_full_description'=>$rs->f('package_full_description'),
  'package_price'=>$rs->f('package_price'),
  'package_maturity_length'=>$rs->f('package_maturity_length'),
  'package_maturity_length_years'=>$rs->f('package_maturity_length_years'),
  'package_interest'=>$rs->f('package_interest'),
  'package_limit_per_month'=>$rs->f('package_limit_per_month'),
  'package_image'=>$rs->f('package_image')
 );
 }
}

include '../skin/admin/ap_header.tpl.php';
include '../skin/admin/ap_package.tpl.php';
include '../skin/admin/ap_footer.tpl.php';
?>

