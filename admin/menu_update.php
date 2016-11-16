<?php
require_once 'class.user.php';
include 'upload_images.php';
include 'resize-class.php';

$user_home = new USER();

/* Parameter */
$menu_id = $_REQUEST["menu_id"];
$t_menu = $_REQUEST['t_menu'];
$t_parent =$_REQUEST['t_parent'];
$t_menu_headline = $_REQUEST['t_menu_headline'];
$menu_desc = $_REQUEST['menu_desc'];
if (isset($_REQUEST['display'])) {	$display = 1; }else{ $display = 0;}
if($_REQUEST['t_parent']=="parent"){ $c_type = 1; $t_main_id =""; }else{$c_type = 2; $t_main_id = $t_parent;}
$t_ordering = $_REQUEST['t_ordering'];

if($_FILES["img_menu"]["name"]!=""){

	$img_menu = $_FILES["img_menu"]["name"];
	$target_dir = '../img/uploads/';
	$img_alert=uploadImage($target_dir,'img_menu');

	//Query INSERT DATA into table MENU with Image Upload
	$stmt_menu= $user_home->runQuery("UPDATE menu SET c_title=:t_menu, c_headline=:t_menu_headline, c_desc=:menu_desc, 
	c_is_show=:display, c_type=:c_type, c_main_id=:t_main_id, c_img=:img_menu, ordering=:t_ordering WHERE c_id=:menu_id");

	$stmt_menu -> execute(array(':t_menu' => $t_menu, ':t_menu_headline'=> $t_menu_headline, ':menu_desc' => $menu_desc, 
	':display' => $display, ':c_type' => $c_type, ':t_main_id'=>$t_main_id,
	':t_ordering' => $t_ordering, ':menu_id'=> $menu_id, ':img_menu'=> $img_menu));

}else{

	//Query INSERT DATA into table MENU without Image Upload
	$stmt_menu= $user_home->runQuery("UPDATE menu SET c_title=:t_menu, c_headline=:t_menu_headline, c_desc=:menu_desc, 
		c_is_show=:display, c_type=:c_type, c_main_id=:t_main_id, ordering=:t_ordering WHERE c_id=:menu_id");

	$stmt_menu -> execute(array(':t_menu' => $t_menu, ':t_menu_headline'=> $t_menu_headline, ':menu_desc' => $menu_desc, 
	':display' => $display, ':c_type' => $c_type, ':t_main_id'=>$t_main_id,
	':t_ordering' => $t_ordering, ':menu_id'=> $menu_id));

}

	

	$msg = "Success! The data have been update.";

echo "<script type='text/javascript'>window.location='../kd-admin.php?page=add_menu&action=edit&menu_id=".$menu_id."&msg=".$msg."&img_smg=".@$img_alert."'</script>";

?>