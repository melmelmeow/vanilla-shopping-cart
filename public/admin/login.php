<?php
include '../includes/config.inc.php';
if(isset($_GET['err']) ){
 $err=$_GET['err'];
 $error_message=$ERROR[$err];
 $u=$_GET['user'];
}
include '../skin/admin/ap_header.tpl.php';
include '../skin/admin/login.tpl.php';
include '../skin/admin/ap_footer.tpl.php';


