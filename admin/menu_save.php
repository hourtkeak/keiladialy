<?php
require_once 'class.user.php';
include 'upload_images.php';
include 'resize-class.php';

$user_home = new USER();

/* Parameter */
$t_menu = $_REQUEST['t_menu'];
$t_parent =$_REQUEST['t_parent'];
$t_menu_headline = $_REQUEST['t_menu_headline'];
$menu_desc = $_REQUEST['menu_desc'];
if (isset($_REQUEST['display'])) {	$display = 1; }else{ $display = 0;}
if($_REQUEST['t_parent']=="parent"){ $c_type = 1; $t_main_id =""; }else{$c_type = 2; $t_main_id = $t_parent;}
if($_FILES["img_menu"]["name"]!=""){

	$img_menu = $_FILES["img_menu"]["name"];
	$target_dir = '../img/uploads/';
	$file_smg=uploadImage($target_dir,'img_menu');

}else{ $img_menu="";}

$t_ordering = $_REQUEST['t_ordering'];

// Query INSERT DATA into table MENU
$stmt_menu= $user_home->runQuery("INSERT INTO menu(c_title, c_headline, c_desc, c_is_show, c_type, c_main_id, c_img, ordering)
	 						value(:t_menu, :t_menu_headline, :menu_desc, :display, :c_type, :t_main_id, :img_menu, :t_ordering)");

$stmt_menu -> execute(array(':t_menu' => $t_menu, ':t_menu_headline'=> $t_menu_headline, ':menu_desc' => $menu_desc, 
	':display'=>$display, ':c_type' => $c_type, ':t_main_id'=>$t_main_id, ':img_menu' => $img_menu,':t_ordering' => $t_ordering));

// GET LAST INSERT ID
 $last_id = $user_home->lasdID();
 if($last_id!=""){
 	$msg = "Success! The data have been insert.";
 }

 echo "<script type='text/javascript'>window.location='../kd-admin.php?page=add_menu&action=edit&menu_id=".$last_id."&msg=".$msg."'</script>";


?>