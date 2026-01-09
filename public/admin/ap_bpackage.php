<?php
//fetch all data from the database
include "../includes/config.inc.php";
include "../includes/db_mysql.inc.php";
include "../includes/category.inc.php";

$uid=isset($_POST['uid']) ? $_POST['uid'] : $_GET['uid'];
$cid=isset($_POST['cid']) ? $_POST['cid'] : $_GET['cid'];

$rs=new Category;

if( !empty($uid) ){
 $opt="where user_id=".$uid;
}

if( !empty($cid) ){
 $opt="where category_id=".$cid;
}



$rs->num_rows();
$sql="SELECT * FROM packages ".$opt;
$rs->query($sql);
$data=array(array());
$num_data=0;

while($rs->next_record()){
 $data[$num_data]['package_id']=$rs->f('package_id');
 $data[$num_data]['package_name']=$rs->f('package_name');
 $data[$num_data]['category_name']=$rs->f('category_id')==0 ? '/' : $rs->getTreeAscName2($rs->f('category_id'));
 $data[$num_data]['package_image']=$rs->f('package_image');
 $num_data++;
}
include '../skin/admin/ap_header.tpl.php';
include '../skin/admin/ap_bpackage.tpl.php';
include '../skin/admin/ap_footer.tpl.php';
?>
