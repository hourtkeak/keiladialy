<div class="box box-solid box-primary ">
  <div class="box-header with-border">
    <h3 class="box-title">MENU</h3>
    <div class="box-tools pull-right">
      <!-- Buttons, labels, and many other things can be placed here! -->
      <!-- Here is a label for example -->
     <a href="kd-admin.php?page=menu_list" class="btn btn-block btn-primary btn-sm"> View all menu</a>
    </div><!-- /.box-tools -->
  </div><!-- /.box-header -->
  <div class="box-body">
	<form  method="POST" enctype="multipart/form-data" action="<?php if(isset($_REQUEST['action'])){echo "admin/menu_update.php";}else{echo "admin/menu_save.php"; }?>">
        <!-- text input -->
        <?php if(isset($_REQUEST['msg'])){ ?>
	        <div class="alert alert-success alert-dismissable">
	            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	            <h4>	<i class="icon fa fa-check"></i> Alert!</h4>
	            <?php echo $_REQUEST['msg']; ?>
	        </div>
        <?php  } ?>

        <?php
        	if(isset($_REQUEST["menu_id"])){
	        	$stmt_menu = $user_home->runQuery("SELECT * FROM menu WHERE c_id=:menu_id"); 
	        	$stmt_menu ->execute(array(':menu_id'=>$_REQUEST['menu_id']));
	        	$rs_menu = $stmt_menu->fetch(PDO::FETCH_ASSOC);
        	}
      	?>

		<div class="form-group">
			<label>Menu</label>
			<input type="text" class="form-control" name="t_menu" placeholder="Enter ..." value="<?php echo @$rs_menu['c_title']; ?>">
		</div>
		<div class="form-group">
			<label>PARENT</label>
			<select class="form-control" name="t_parent">
				<option value="parent"> --Please Select-- </option>
				<?php
					$parent_sql =$user_home->runQuery("SELECT c_id, c_title FROM menu WHERE c_type =:num");
					$parent_sql->execute(array(':num'=>1));
                    while($result_parent=$parent_sql->fetch(PDO::FETCH_ASSOC)){
                if($result_parent['c_id']==$rs_menu['c_main_id']){$select= "selected";}else{$select="";};
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
			<label>Headline</label>
			<input type="text" name="t_menu_headline" class="form-control" value="<?php echo @$rs_menu['c_headline'];?>" placeholder="Enter ...">
		</div>
    
		<div class="form-group">
			<label>Description</label>
			<div class="box-body pad">
					<textarea name="menu_desc" class=" textarea1 form-control  tinymce"  rows="15" style="width:100%; height: 300px;"><?php echo @$rs_menu['c_desc'];?></textarea>
				
			</div>
    	</div>
    	<div class="form-group">
			<label>Ordering</label>
			<input type="number" value="<?php echo @$rs_menu['ordering'];?>" name="t_ordering" class="form-control" placeholder="Enter ...">
		</div>
		<div class="form-group">
			<label for="exampleInputFile">Upload Images</label>
			<input type="file" id="exampleInputFile" name="img_menu">
			<p class="help-block">Icon or Photo</p>

			<?php if(isset($_REQUEST['img_smg'])){ echo '<p>'.@$_REQUEST['img_smg'].'</p>';}?>
				
			<?php
			   if(@$rs_menu['c_img']!=""){
			?>
			   	<img src="img/uploads/<?php echo $rs_menu['c_img']; ?>" style="width:200px;">
			<?php
			   } 
			 ?>
         </div>
          <div class="form-group">
			<div class="checkbox">
				<label>
				  <input type="checkbox" name="display" <?php if(@$rs_menu['c_is_show']==1) echo 'checked="checked"'; ?>>
				  Check is show / Uncheck is unshow
				</label>
			</div>	
		</div>
		<input type="hidden" name="menu_id" value="<?php echo @$rs_menu['c_id'];?>">
	
  </div><!-- /.box-body -->
  <div class="box-footer">
    <button type="submit" class="btn btn-primary">Submit</button>
    
  </div><!-- box-footer -->
  </form>
</div><!-- /.box -->