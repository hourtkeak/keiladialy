 
<?php if($row['level']==1){ ?>
 <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li class="header">HEADER</li>
        <!-- Optionally, you can add icons to the links -->
        <li class="active"><a href="kd-admin.php"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
        <li class="treeview">
          <a href="#"><i class="fa fa-fw fa-user"></i> <span>User Setting</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="kd-admin.php?page=user_list">User</a></li>
            <li><a href="kd-admin.php?page=user">Register</a></li>
            <li><a href="admin/fpass.php">Reset Password</a></li>
          </ul>
        </li>
      </ul>
      <!-- /.sidebar-menu -->
<?php }elseif ($row['level']==2) { ?>
      
      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li class="header">HEADER</li>
        <!-- Optionally, you can add icons to the links -->
        <li class="active"><a href="kd-admin.php"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
        <li><a href="kd-admin.php?page=slideshow"><i class="fa fa-fw fa-file-image-o"></i> <span>Slide Show</span></a></li>
        <li class="treeview">
          <a href="#"><i class="fa fa-fw fa-newspaper-o"></i> <span>Article</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li><a href="kd-admin.php?page=content">Add New Article</a></li>
            <li><a href="kd-admin.php?page=content_list">Article List</a></li>
          </ul>
        </li>
      </ul>
      <!-- /.sidebar-menu -->
<?php }elseif ($row['level']==3) { ?>
        <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li class="header">HEADER</li>
        <!-- Optionally, you can add icons to the links -->
        <li class="active"><a href="kd-admin.php"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
        <li class="treeview">
          <a href="#"><i class="fa fa-fw fa-newspaper-o"></i> <span>Article</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li><a href="kd-admin.php?page=content">Add New Article</a></li>
            <li><a href="kd-admin.php?page=content_list">Article List</a></li>
          </ul>
        </li>
      </ul>
      <!-- /.sidebar-menu -->

<?php } ?>