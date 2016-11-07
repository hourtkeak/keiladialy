
 <div class="box">
  <div class="box-header">
    <h3 class="box-title" style="padding-right:20px;">MENU List </h3>
    <a href="sr-admin.php?page=add_menu" class="btn btn-primary btn-sm"> Add new menu</a>
    <div class="box-tools">
      <div class="input-group" style="width: 150px;">

        <input type="text" name="table_search" class="form-control input-sm pull-right" placeholder="Search">
        <div class="input-group-btn">
          <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
        </div>
      </div>
    </div>
  </div><!-- /.box-header -->
  <div class="box-body table-responsive no-padding">
    <table class="table table-hover">
      <tr>
        <th>ID</th>
        <th>Menu Title</th>
        <th>Parent</th>
        <th>Head Line</th>
          <th>Display</th>
        <th>Action</th>
      </tr>
       <?php 
            if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete'){
              $stmt_d = $DB_con->prepare("DELETE FROM menu where  c_id =:menu_id");
              $stmt_d -> execute(array(':menu_id'=> $_REQUEST['menu_id']));
          }
        ?>

       <?php
          $stmt_menu = $DB_con->prepare("SELECT f.c_id, f.c_title, f.c_is_show, f.c_headline, f.c_main_id, (SELECT s.c_title FROM menu as s
          WHERE s.c_id=f.c_main_id) as parent FROM menu as f"); 
          $stmt_menu -> execute();
          while($result_menu = $stmt_menu->fetch(PDO::FETCH_ASSOC)){
        ?>
       
      <tr>
        <td> <?php echo $result_menu['c_id'];?> </td>
        <td> <?php echo $result_menu['c_title'];?> </td>
        <td> <?php echo $result_menu['parent'];?> </td>
        <td> <?php echo $result_menu['c_headline'];?></td>
        <td> <?php echo $result_menu['c_is_show'];?></td>
        <td>
         
          
          <a href="sr-admin.php?page=menu_list&menu_id=<?php echo $result_menu['c_id'];?>&action=delete" class="btn btn-danger btn-xs" style="width:50px;"><i class="fa fa-fw fa-close"></i></a>
          
         <a href="sr-admin.php?page=add_menu&action=edit&menu_id=<?php echo $result_menu['c_id'];?>" class="btn  btn-warning btn-xs" style="width:50px;"><i class="fa fa-fw fa-edit"></i></a>
         <?php if($result_menu['c_main_id']==2){?>
          <a href="sr-admin.php?page=add_images&item_id=<?php echo $result_menu['c_id'];?>&images_type=d" class="btn btn-default btn-xs" style="width:50px;"><i class="fa fa-fw fa-file-image-o"></i></a>
          <?php } ?>
        </td>
      </tr>
      <?php } ?>
    </table>
  </div><!-- /.box-body -->
  <div class="box-footer clearfix">
    <ul class="pagination pagination-sm no-margin pull-right">
      <li><a href="#">&laquo;</a></li>
      <li><a href="#">1</a></li>
      <li><a href="#">2</a></li>
      <li><a href="#">3</a></li>
      <li><a href="#">&raquo;</a></li>
    </ul>
  </div>
</div><!-- /.box -->
