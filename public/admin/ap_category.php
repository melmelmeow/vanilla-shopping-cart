<?php
session_start();
if($_SESSION['admin_name']=="" || $_SESSION['admin_name']!='admin'){
 header('Location:login.php');
}
$admin_name=$_SESSION['admin_name'];

include '../includes/config.inc.php';
require_once '../includes/db_mysql.inc.php';
require_once '../includes/category.inc.php';

$rsCategory=new Category;
$dCategory=array();
$errors=array();
$aERR=array();
$select_contents=explode(";",$rsCategory->arrTreeName2());
array_pop($select_contents);

$cid=isset($_POST['cid']) ? $_POST['cid'] : $_GET['cid'];
$pid=isset($_POST['pid']) ? $_POST['pid'] : $_GET['pid'];

if( !empty($pid) && !empty($cid) ){
 $dCategory=$rsCategory->getAllValues($cid);
 $category_id=$cid;
 $category_name=$dCategory['category_name'];
 $parent_category_id=$dCategory['parent_category_id'];
 $category_description=$dCategory['category_description'];
}elseif(isset($_GET['err'])){
 $category_name=$_GET['category_name'];
 $parent_category_id=$_GET['parent_category_id'];
 $category_description=html_entity_decode($_GET['category_description']);
 $errors=explode(',',$_GET['err']);
 $num_errors=$_GET['err_count'];
 $error_count=$num_errors;
 $error_count.=$num_errors > 1 ? ' errors found' : ' error found';
 
}elseif(!array_key_exists($pid,$process)){
 header("Location: 404.html");
}

include '../skin/admin/ap_header.tpl.php';
include '../skin/admin/ap_category.tpl.php';
include '../skin/admin/ap_footer.tpl.php';
?>
