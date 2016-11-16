<?php
session_start();
date_default_timezone_set('Asia/Bangkok');
require_once 'class.user.php';
$user_home = new USER();
include("resize-class.php");

// Variable
$user_id=$_SESSION['userSession'];
$article_type= @$_POST["article_type"];
$article_title=@$_POST["article_title"];
$short_article=@$_POST["short_article"];
$full_article =@$_POST["full_article"];

$date=date("F j, Y");

if(isset($_POST['show'])){$show=1;}else{$show=0;}
if(@$_POST['txt_feature']!=null){$feature=1;}else{$feature=0;}

$sort=@$_POST['sort'];
$title = @$_POST['title_name'];
$thumbnail = @$_FILES['image_name']["name"];
$original_image = @$_FILES['image_name']["name"];
$filetype=@$_FILES['image_name']["type"];
$filenametemp=@$_FILES['image_name']["tmp_name"];
$fileerror=@$_FILES['image_name']["error"];
$img_title=@$_POST['img_title'];

//insert article query
if($article_title!="" and $full_article!=""){
  
  $sql=$user_home->runQuery("INSERT INTO 
  	content(cat_id, text_title, description, full_text, modified_date, member_id, display, sort, feature) 
  	value('$article_type', :article_title, :short_article, :full_article,'$date','$user_id','$show','$sort', '$feature')");
  $sql->execute(array(':article_title'=>$article_title, ':short_article'=>$short_article, ':full_article'=>$full_article));

}else{echo "<script type='text/javascript'>window.location='../kd-admin.php?op=article&msg=require&action=form'</script>";}

	// function moving images
	function moveFile($fullname, $filename, $filetype, $filenametemp, $fileerror){// fullname = path + filename
		if($filename!=null){
			if(($filetype=="image/jpg")
			 ||($filetype=="image/jpeg")
			 ||($filetype=="image/png")
			 ||($filetype=="image/gif")
			 ||($filetype=="application/x-shockwave-flash") && $_FILES[$filename]["size"]<200000)
			 {
					if($fileerror>0){
						echo "Error: ".$filename. "<br />";
					}elseif(file_exists($fullname))
						echo $filename." already exists. ";
					else
						move_uploaded_file($filenametemp,$fullname);

			}else{
							echo "Invalid file!";
			}
		}

	}
// query id have been insert
$sqlselect =$user_home->runQuery("SELECT id FROM content order by id DESC limit 1");
$sqlselect->execute();
$resultid=$sqlselect->fetch(PDO::FETCH_ASSOC);
$new_id=$resultid['id'];
	//Crop images
	if(!empty($thumbnail)){
				// move files
				$full_name_original = "../img/uploads/".$original_image;
				$full_name_thumb = "../img/uploads/thumbs/".$thumbnail;
				moveFile($full_name_original, $original_image, $filetype, $filenametemp, $fileerror);
				// *** 1) Initialise / load image
				$resizeObj = new resize($full_name_original);
				// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
				$resizeObj -> resizeImage(553,'auto', 'auto');
				// *** 3) Save image
				$resizeObj -> saveImage($full_name_thumb, 100);
				// insert images

			  $slqimg=$user_home->runQuery("INSERT INTO images(id_article, img_title, img_thumbnail, img_original) value('$new_id', '$img_title','$thumbnail', '$original_image')");
			  $slqimg->execute();
			}


	//Redirect to form page
	echo "<script type='text/javascript'>window.location='../kd-admin.php?page=content&action=edit&msg=insert&id_article=".$new_id."'</script>";
?>
