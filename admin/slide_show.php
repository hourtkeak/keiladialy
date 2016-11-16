<?php 
include_once 'upload_images.php';
	
 	if(@$_REQUEST['action']=="Add slide"){
 		
 		/* Move Large image */
 		$target_dir_l = 'img/uploads/';
 		$img_smg_l=uploadImage($target_dir_l, 'img_slide');
 		$img_slide = $_FILES['img_slide']['name'];

 		$stmt_img = $user_home->runQuery("INSERT INTO slide(s_title, s_description, s_img, link, ordering) 
 									VALUES (:slide_title, :slide_desc, :img_slide, :link_target, :ordering)");
 		
 		$stmt_img -> execute (array(':slide_title'=>$_REQUEST['slide_title'], ':slide_desc' => $_REQUEST['slide_desc'], 
 										':img_slide' => $img_slide, ':link_target' => $_REQUEST["link_target"]
 										, ':ordering'=> $_REQUEST['ordering']));

 		$smg = '<div class="alert alert-success alert-dismissable">
	            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	            <h4>	<i class="icon fa fa-check"></i> Alert!</h4>
	            Success! The data have been added.	        </div>';

 	}elseif(@$_REQUEST['action'] == "edit") {

		$stmt_sile_edit = $user_home->runQuery("SELECT * FROM slide WHERE slide_id =:s_id");
        $stmt_sile_edit -> execute(array(':s_id' => $_REQUEST['s_id'])); 
        $rs_slide = $stmt_sile_edit -> fetch(PDO::FETCH_ASSOC);

        if (isset($_REQUEST["u_success"])) {
        	
       
 				$smg = '<div class="alert alert-success alert-dismissable">
	            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	            <h4>	<i class="icon fa fa-check"></i> Alert!</h4>
	            Success! The data have been Updated. </div>';
	     }

 		
	}elseif(isset($_REQUEST['action']) && $_REQUEST['action']=="Save slide"){
 		
 		/* Move Large image */
 		$target_dir_l = 'img/uploads/';
 		$img_smg_l=uploadImage($target_dir_l, 'img_slide');
 		$img_slide = $_FILES['img_slide']['name'];
 		$s_id = $_REQUEST['s_id'];

 		if($img_slide!=""){
 			$stmt_img = $user_home->runQuery("UPDATE slide SET s_title=:slide_title, 
 										s_description=:slide_desc, s_img=:img_slide, link=:link_target, ordering=:ordering WHERE slide_id=:s_id"); 
 							
 			$stmt_img -> execute (array(':slide_title'=>$_REQUEST['slide_title'], ':slide_desc' => $_REQUEST['slide_desc'], 
 										':img_slide' => $img_slide, ':link_target' => $_REQUEST["link_target"], ':s_id' => $_REQUEST['s_id'], ':ordering'=>  $_REQUEST['ordering'] ));
 			echo '<script type="text/javascript">window.location = "kd-admin.php?page=slideshow&action=edit&s_id='.$s_id.'&u_success=true"</script>';
 		}else{
 			$stmt_img = $user_home->runQuery("UPDATE slide SET s_title=:slide_title, 
 										s_description=:slide_desc, link=:link_target, ordering=:ordering WHERE slide_id=:s_id"); 
 							
 			$stmt_img -> execute (array(':slide_title'=>$_REQUEST['slide_title'], ':slide_desc' => $_REQUEST['slide_desc'], ':link_target' => $_REQUEST["link_target"], ':s_id' => $_REQUEST['s_id'], ':ordering'=>  $_REQUEST['ordering'] ));
 			   
 			echo '<script type="text/javascript">window.location = "kd-admin.php?page=slideshow&action=edit&s_id='.$s_id.'&u_success=true"</script>';
 		}
 		
 	}elseif (isset($_REQUEST['action']) && $_REQUEST['action']=="delete") {
 		$stmt_d = $user_home->runQuery("DELETE FROM slide WHERE slide_id =:s_id");
 		$stmt_d -> execute(array(':s_id' => $_REQUEST['s_id']));
			$smg_d_success = '<div class="alert alert-success alert-dismissable" style="margin:10px;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>	<i class="icon fa fa-check"></i> Alert!</h4>
            Success! The data have been Deleted. </div>';
 	}
 ?>

<div class="row">
	<div class="col-md-6">
		<div class="box box-solid box-primary ">
		  <div class="box-header with-border">
		    <h3 class="box-title">Slide Show</h3>
		    <div class="box-tools pull-right">
           <a href="kd-admin.php?page=slideshow" class="btn btn-block btn-primary btn-sm">Add Slide </a>
          </div>
		    <div class="box-tools pull-right">
		      <!-- Buttons, labels, and many other things can be placed here! -->
		      <!-- Here is a label for example -->
		    </div><!-- /.box-tools -->
		  </div><!-- /.box-header -->
		  <div class="box-body">
		  	
		  	<?php echo @$smg;?>
			<form  method="POST" enctype="multipart/form-data" action="">
		        <!-- text input -->
				<div class="form-group">
					<label>Slide Title</label>
					<input type="text" class="form-control" name="slide_title" placeholder="Enter ..." value="<?php echo @$rs_slide['s_title']; ?>">
				</div>
				
				<div class="form-group">
					<label>Slide Subtitle</label>
					<input type="text" name="slide_desc" class="form-control" value="<?php echo @$rs_slide['s_description']; ?>" placeholder="Enter ...">
				</div>
				 <div class="form-group">
					<label for="exampleInputFile">Upload Images Large</label>
					<input type="file" name="img_slide">
					<p class="help-block"> Width: 752px x Height: 468 px </p>

					<?php if(@isset($_REQUEST['action'])){ echo '<p>'.@$img_smg_l.'</p>';}?>
						
					<?php
					   if(@$rs_slide['s_img']!=""){
					?>
					   	<img src="img/uploads/<?php echo $rs_slide['s_img']; ?>" style="width:200px;">
					<?php
					   } 
					 ?>
		         </div>

		         <div class="form-group">
		         	<label>link</label>
					<input type="text" class="form-control" name="link_target" value="<?php echo @$rs_slide['link']; ?>">
				</div>
				<div class="form-group">
				<label>Ordering</label>
					<input type="number" class="form-control" name="ordering" value="<?php echo @$rs_slide['ordering']; ?>">
				</div>
				<input type="hidden" name="s_id" value="<?php echo @$rs_slide['slide_id'];?>">
		  </div><!-- /.box-body -->
		  <div class="box-footer">
		    <input type="submit" class="btn btn-primary" name="action" value="<?php if(@$_REQUEST['action']=="edit"){ echo 'Save slide';}else{echo 'Add slide';} ?>">
		    
		  </div><!-- box-footer -->
		  </form>
		</div><!-- /.box -->
	</div>
	<div class="col-md-6">
		<div class="box">
            <div class="box-header">
              <h3 class="box-title">Slide List</h3>
            </div><!-- /.box-header -->
            <div class="box-body no-padding">
         		<?php echo @$smg_d_success;?>
              <table class="table table-condensed">
                <tbody>
                <tr>
                 	<th style="width: 10px">ID</th>
                  	<th>Title</th>
                  	<th>Ordering</th>
                  	<th>Images</th>
                  	<th style="width: 40px">Action</th>
                </tr>
                <?php 
            		$stmt_slide_list = $user_home->runQuery("SELECT * FROM slide");
            		$stmt_slide_list -> execute(); 
                	while ($rs_slide_list=$stmt_slide_list->fetch(PDO::FETCH_ASSOC)){
                ?>​
                <tr>
                  	<td><?php echo $rs_slide_list['slide_id'];?></td>
                 	<td><?php echo $rs_slide_list['s_title'];?></td>
                 	<td><?php echo $rs_slide_list['ordering'];?></td>
                  	<td class="img-slide">
                   		<img src="img/uploads/<?php echo $rs_slide_list['s_img']; ?>" class="photo_in_list">
                 	</td>
                  	<td> <a href="kd-admin.php?page=slideshow&s_id=<?php echo $rs_slide_list['slide_id']; ?>&action=delete" class="btn btn-danger btn-xs" style="width:50px;"><i class="fa fa-fw fa-close"></i></a>
          
         				<a href="kd-admin.php?page=slideshow&s_id=<?php echo $rs_slide_list['slide_id']; ?>&action=edit" class="btn  btn-warning btn-xs" style="width:50px;"><i class="fa fa-fw fa-edit"></i></a>
         			</td>
                </tr>
                <?php
                	}
                ?>
              	</tbody>
              </table>
            </div><!-- /.box-body -->
          </div>	
	</div>
</div>
