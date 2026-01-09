<?php
session_start();
include "../includes/config.inc.php";
include "../includes/db_mysql.inc.php";
include "../includes/category.inc.php";
include "../includes/functions.inc.php";

$rs=new DB_Sql;
$cat=new Category;
$lcategory=array();
$error=array();
$error_count=0;

$category_name=$_POST['category_name'];
$parent_category_id=$_POST['parent_category_id'];
$category_description=$_POST['category_description'];
$lcategory=$cat->getAllValues($parent_category_id);
if($parent_category_id==0){
 $parent_category_name='Root';
}else{
 $parent_category_name=$lcategory['category_name'];
}

$category_description=$_POST['category_description'];

if(!isset($_POST['pid'])){
 header("Location: 404.html");
}
if(!isset($_POST['cid'])){
 header("Location: 404.html");
}



$pid=$_POST['pid'];
switch($pid){
 case 1:
  if($category_name==''){
    $bolError=TRUE;
	array_push($error,1);
	$error_count++;
	
  }
  /*
  if($category_description==''){
   $bolError=TRUE;
   array_push($error,2);
   $error_count++;
  }
  */
 
 if($bolError){
  header("Location:ap_category.php?pid=".$pid."&err=".arr_build_query($error).'&err_count='.$error_count.'&category_name='.$category_name.'&parent_category_id='.$parent_category_id.'&category_description='.htmlentities(urlencode($category_description)));
 }else{
 $data=array(
  'category_name'=>$category_name,
  'parent_category_id'=>$parent_category_id,
  'category_description' =>$category_description
 );
 $rs->insert('categories',$data);
 }
 $status='Created';
 break;
 case 2:
  if($category_name==''){
    $bolError=TRUE;
	array_push($error,1);
	$error_count++;
  }
   /*
  if($category_description==''){
   $bolError=TRUE;
   array_push($error,2);
   $error_count++;
  }
  */
  
 if($bolError){
  header("Location:ap_category.php?pid=".$pid."&err=".arr_build_query($error).'&err_count='.$error_count.'&category_name='.$category_name.'&parent_category_id='.$parent_category_id.'&category_description='.htmlentities(urlencode($category_description)));
 }else{
  $data=array(
   'category_name'=>$category_name,
   'parent_category_id'=>$parent_category_id,
   'category_description'=>$category_description
 );
 $rs->update('categories',$data,'category_id='.$cid);
 }
 $status='Modified';
 break;
 
 case 3:
 $lcategory=$cat->getAllValues($cid);
 $category_name=$lcategory['category_name'];
 if($lcategory['parent_category_id']==0){
  $parent_cateogory_name='Root';
 }else{
  $parent_category_name=$cat->getColumnValue($lcategory['parent_category_id'],'category_name');
 }
 $category_description=$lcategory['category_description'];
 $rs->delete('categories','category_id='.$cid);
 $status='Removed';
 break;
 default:
  header("Location : 404.html");
  break;
}
include '../skin/admin/ap_header.tpl.php';
include '../skin/admin/ap_pcat.tpl.php';
include '../skin/admin/ap_footer.tpl.php';
?>
