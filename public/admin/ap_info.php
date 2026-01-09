<?php
session_start();
if($_SESSION['admin_name']=="" || $_SESSION['admin_name']!='admin'){
 header('Location:login.php');
}
$admin_name=$_SESSION['admin_name'];
include "../includes/config.inc.php";

include '../skin/admin/ap_header.tpl.php';
include '../skin/admin/ap_info.tpl.php';
include '../skin/admin/ap_footer.tpl.php';
?>
