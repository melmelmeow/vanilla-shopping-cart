<?php
//fetch all data from the database
include "../includes/config.inc.php";
include "../includes/db_mysql.inc.php";
include "../includes/category.inc.php";

$rs=new Category;
$sql="SELECT * FROM categories";
$rs->query($sql);
$rs->num_rows();




$data=array(array());
$num_data=0;

while($rs->next_record()){
 $data[$num_data]['category_id']=$rs->f('category_id');
 $data[$num_data]['category_name']=$rs->f('category_name');
 $data[$num_data]['parent_category_name']=$rs->f('parent_category_id')==0 ? '/' : $rs->getTreeAscName2($rs->f('parent_category_id'));
 $data[$num_data]['user_description']=$rs->f('user_description');
 $num_data++;
}
include '../skin/admin/ap_header.tpl.php';
include '../skin/admin/ap_bcat.tpl.php';
include '../skin/admin/ap_footer.tpl.php';
?>