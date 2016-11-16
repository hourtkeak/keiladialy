<?php
require_once 'class.user.php';
$user_home = new USER();

$id_article=$_GET["id_article"];
$sql_delete=$user_home->runQuery("DELETE FROM content WHERE id=:id_article");
$sql_delete->execute(array(':id_article' => $id_article ));


$sql_delete_img=$user_home->runQuery(
 "DELETE FROM images WHERE id_article=:id_article"
  );
$sql_delete_img->execute(array(':id_article' => $id_article ));

//Redirect to form page
echo "<script type='text/javascript'>window.location='../kd-admin.php?page=content_list'</script>";
?>
