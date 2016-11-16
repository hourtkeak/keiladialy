<?php
date_default_timezone_set('Asia/Bangkok');
require_once 'class.user.php';
include("resize-class.php");

$user_home = new USER();
// Variable
$id_article=@$_POST["id_article"];
$article_type= @$_POST["article_type"];
$article_title=@$_POST["article_title"];
$short_article=@$_POST["short_article"];
$full_article =@$_POST["full_article"];
$date=date("F j, Y");
if(!empty($_POST['show'])){$show=1;}else{$show=0;}

if(!empty($_POST['txt_feature'])){$feature=1;}else{$feature=0;}

$sort=@$_POST['sort'];
$title = @$_POST['title_name'];
$thumbnail = @$_FILES['image_name']["name"];
$original_image = @$_FILES['image_name']["name"];
$filetype=@$_FILES['image_name']["type"];
$filenametemp=@$_FILES['image_name']["tmp_name"];
$fileerror=@$_FILES['image_name']["error"];
$img_title=@$_POST['img_title'];

//update article query

$sql =$user_home->runQuery("UPDATE content SET cat_id='$article_type', text_title=:article_title, description=:short_article, full_text=:full_article, modified_date='$date', display='$show', sort=:sort, feature='$feature' WHERE id=:id_article");
$sql-> execute(array(':id_article' => $id_article, ':full_article'=> $full_article, ':article_title'=>$article_title, ':short_article'=>$short_article, ':sort'=>$sort));
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
					// $sql_check=mysqli_query($mysqli, "SELECT id_article FROM images where id_article='$id_article'");
					$sql_check=$user_home->runQuery("SELECT id_article FROM images where id_article=:id_article");
					$sql_check->execute(array(':id_article' => $id_article ));
					$count=$sql_check->rowCount();

					if($count!=0){

						$slqimg=$user_home->runQuery("UPDATE images SET img_title=:img_title, img_thumbnail='$thumbnail',
											 			img_original='$original_image'
														WHERE id_article=:id_article");
						$slqimg->execute(array(':id_article' => $id_article, ':img_title'=>$img_title));
					}else{
						// $slqimg=mysqli_query($mysqli, "INSERT INTO images(id_article, img_title, img_thumbnail, img_original) value('$id_article', '$img_title','$thumbnail', '$original_image')");
						$slqimg=$user_home->runQuery("INSERT INTO images(id_article, img_title, img_thumbnail, img_original) value('$id_article', '$img_title','$thumbnail', '$original_image')");
						$slqimg->execute();

						}
			}else{

				  $slqimg=$user_home->runQuery("UPDATE images SET img_title=:img_title WHERE id_article=:id_article");
				  $slqimg->execute(array(':id_article' => $id_article, ':img_title' => $img_title));
				}
//Redirect to form page
echo "<script type='text/javascript'>window.location='../kd-admin.php?page=content&action=edit&msg=update&id_article=".$id_article."'</script>";
?>