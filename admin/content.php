<?php
$id_article=@$_GET['id_article'];
?>
<?php
      $sql_article= $user_home->runQuery(
                "SELECT
                  content.id,
                  content.text_title,
                  content.description,
                  content.display,
                  content.sort,
                  content.feature,
                  content.full_text,
                  content.cat_id,
                  menu.c_id,
                  menu.c_title,
                  images.img_thumbnail,
                  images.img_title
            FROM content
             LEFT JOIN tbl_users  ON content.member_id=tbl_users .userID
            LEFT JOIN menu ON content.cat_id=menu.c_id
            LEFT JOIN images ON content.id=images.id_article where content.id=:id_article");
$sql_article->execute(array(":id_article"=>$id_article ));
$result_article=$sql_article->fetch(PDO::FETCH_ASSOC);
$id_cat =$result_article['cat_id'];
            ?>
									<?php
									// alert message check
									if(@$_GET["msg"]=='insert'){?>
                                        <div class="alert alert-success alert-dismissable">
                                            <i class="fa fa-check"></i>
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <b>Alert!</b> Success Insert your article, please review text in form below!
                                        </div>
                                    <?php
									}elseif(@$_GET["msg"]=='update'){
									?>
										<div class="alert alert-success alert-dismissable">
                                        	<i class="fa fa-check"></i>
                                        	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        	<b>Alert!</b> Success Update you article!
                                   	 	</div>
									<?php }elseif(@$_GET["msg"]=='require'){?>
										<div class="alert alert-danger alert-dismissable">
                                            <i class="fa fa-ban"></i>
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <b>Alert!</b> Title Article and Full Text is Reqiure
                                    	</div>
									<?php
										}
									?>

<div class="box box-solid box-primary ">
  <div class="box-header with-border">
    <h3 class="box-title">Travel BLog</h3>
    <div class="box-tools pull-right">
      <!-- Buttons, labels, and many other things can be placed here! -->
      <!-- Here is a label for example -->
     <a href="kd-admin.php?page=content_list" class="btn btn-block btn-primary btn-sm"> View all menu</a>
    </div><!-- /.box-tools -->
  </div><!-- /.box-header -->
  <div class="box-body">
<form method="post" enctype="multipart/form-data" action="<?php
if(@$_GET['action']=="edit"){
	echo 'admin/content_update.php';
}else{
	echo 'admin/content_save.php';
	}

?>">

	<div class="form-group">
        <label>Content Categories</label>
        <select class="form-control" name="article_type">
          <option value="parent"> -- Please Select -- </option>
				<?php
					$parent_sql =$user_home->runQuery("SELECT c_id, c_title FROM menu WHERE c_type =:num and c_main_id=:main_id");
					$parent_sql->execute(array(':num'=>2, ':main_id' =>15 ));
                    while($result_parent=$parent_sql->fetch(PDO::FETCH_ASSOC)){
                if($result_parent['c_id']==$result_article['cat_id']){$select= "selected";}else{$select="";};
                ?>

                <option value="<?php echo $result_parent['c_id']; ?>" <?php echo $select; ?> >
               		 <?php echo $result_parent['c_title'];?> 
                </option>
                
                <?php	
                    }
				 ?>
        </select>
	</div>
    <div class="form-group">
      <label>Title</label>
      <input type="text" name="article_title" class="form-control" value="<?php echo @$result_article["text_title"]; ?>"/>
	</div>
	<div class="form-group">
     	<label>Short Description</label>
    	<textarea class="form-control"  name="short_article" style="width:100%; height: 100px;"><?php echo @$result_article["description"]; ?></textarea>
	</div>
     <div class="form-group">
		<label>Detail Conetnt</label>
		<textarea class="textarea1" name="full_article" class="form-control tinymce"  rows="15"><?php echo @$result_article["full_text"]; ?></textarea>
    </div>
    <div class="form-group">
        <label for="exampleInputFile">Images</label>
        <input type="file" name="image_name" id="image_name">
        <p class="help-block">Width: 640 x Height: 425</p>
        <?php if(!empty($result_article['img_thumbnail'])){
        echo '<img src="img/uploads/thumbs/'.$result_article['img_thumbnail'].'"/>';
		}else{echo "";}?>
    </div>

     <div class="form-group">
      <label>Images title</label>
      <input type="text" name="img_title" class="form-control" value="<?php echo @$result_article['img_title']; ?>"/>
	   </div>

    <div class="checkbox">
        <label>
        	<?php if($result_article['display']==1) $check='checked="checked"'; else $check='';?>
            <input type="checkbox" name="show"<?php echo $check; ?> /> Display Option<span class="help-block">(Check=show, Uncheck=unshow)</span>
        </label>
    </div>
    <?php if($row['level']==2 or $row['level']==1){ ?>
    <div class="checkbox">
        <label>
          <?php if($result_article['feature']==1) $check='checked="checked"'; else $check='';?>
            <input type="checkbox" name="txt_feature" <?php echo $check; ?> /> Feature Article<span class="help-block">(Check=show, Uncheck=unshow)</span>
        </label>
    </div>
    <?php } ?>
         
      
     <div class="form-group">
      <label>Ordering</label>
      <input type="text" name="sort" class="form-control" style="width:100px" value="<?php echo @$result_article['sort'];?>"/>
	</div>
   
    <input type="hidden" value="<?php echo $result_article['id'];?>" name="id_article" />
 

  </div><!-- /.box-body -->
  <div class="box-footer">
    <button type="submit" class="btn btn-primary"><?php if(@$_GET['action']!='edit') echo 'Submit'; else echo 'Save'; ?></button>
    
  </div><!-- box-footer -->
  </form>
</div><!-- /.box -->