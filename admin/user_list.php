<?php 
if (@$_REQUEST['action']=="delete") {
  $stmt_delete = $user_home->runQuery("DELETE FROM tbl_users WHERE userID = :uid");
  $stmt_delete->execute(array(':uid' => $_REQUEST['uid']));
  $msg = "<div class='alert alert-success' style='margin:10px;'>
            <button class='close' data-dismiss='alert'>&times;</button>
            <strong>Success!</strong> Data have been deleted!
            </div>";
}

?>

<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">User Lists</h3>

               <div class="box-tools pull-right">
           <a href="kd-admin.php?page=user" class="btn btn-block btn-primary btn-sm">Add new user </a>
          </div>
            </div>
            
            <div class="box-body table-responsive no-padding">
              <!-- /.box-header -->
            <?php if (isset($_REQUEST["action"])){
              echo $msg;
            }?>
              <table class="table table-hover">
                <tbody><tr>
                  <th>ID</th>
                  <th>User</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>E-mail</th>
                  <th>Action</th>
                </tr>
                <?php 
                  $stmt_user = $user_home->runQuery("SELECT * FROM tbl_users");
                  $stmt_user->execute();
                  while ($row_user = $stmt_user->fetch(PDO::FETCH_ASSOC)){
                ?>
                <tr>
                  <td><?php echo $row_user['userID']; ?></td>
                  <td><?php echo $row_user['displayName']; ?></td>
                  <td><?php echo $row_user['create_date']; ?></td>
                  <td>
                      <?php 
                        if($row_user['userStatus']=="Y"){ echo '<span class="label label-success">Activated</span>';}else{
                          echo '<span class="label label-danger">pedding</span>';
                        }
                      ?>
                      

                  </td>
                  <td><?php echo $row_user['userEmail']; ?></td>
                  <td>
                      <a href="kd-admin.php?page=user&action=edit&uid=<?php echo $row_user['userID'];?>" type="button" class="btn btn-primary btn-sm"><i class="fa fa-fw fa-edit"></i></a>  &nbsp;  &nbsp;  &nbsp;
                      <a href="kd-admin.php?page=user_list&action=delete&uid=<?php echo $row_user['userID'];?>" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-times"></i></button>

                  </td>
                </tr>
                <?php } ?>
              </tbody></table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>