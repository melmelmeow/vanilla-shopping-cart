<?php
session_start();
if($_SESSION['user_name']=="" || $_SESSION['user_name']!='admin'){
 header('Location:login.php');
}
include '../includes/config.inc.php';
require_once '../includes/db_mysql.inc.php';
require_once '../includes/category.inc.php';

$rsCategory=new Category;
$select_contents=explode(";",$rsCategory->arrTreeName2());
array_pop($select_contents);
$pid=$_GET['pid'];

if(!array_key_exists($pid,$process)){
  header("Location: 404.html");
}

?>
<form method="post" action="ap_category.php" name="category" >
<input type="hidden" name="cid" value="<?php echo $cid?>"/>
<input type="hidden" name="pid" value="<?php echo $pid?>" />
<table>
<tr>
<td valign="top">
 <select name="cid">
 <?php foreach($select_contents as $i){
  list($id,$name)=explode(">",$i);
 ?>
 <option value="<?php echo $id?>">/<?php echo $name?></option>
 <?php } ?>
 </select>

</td>
<td><input type="Submit" value="<?php echo $process[$pid];?>" /></td>
</tr>
</table>
</form>