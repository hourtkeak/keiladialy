 <!-- Your Page Content Here -->
      <!--info box-->
      <div class="row">
        
       

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-fw fa-newspaper-o"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Number Of Article</span>
              <span class="info-box-number">
                <?php $stmt_numarticle= $user_home->runQuery("SELECT count(*) AS num_article FROM Content"); 
                      $stmt_numarticle->execute();
                      $rs_num_article = $stmt_numarticle-> fetch(PDO::FETCH_ASSOC);
                      echo $rs_num_article['num_article'];
                ?>
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">User Registered</span>
              <span class="info-box-number">
                  <?php $stmt_unum= $user_home->runQuery("SELECT count(*) AS unum FROM tbl_users"); 
                    $stmt_unum->execute();
                    $rs_unum = $stmt_unum->fetch(PDO::FETCH_ASSOC);
                    echo $rs_unum['unum'];
                  ?>
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <div class="row">
        <div class="col-md-12">
            <!-- PRODUCT LIST -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Recently Added Article</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box">
                <?php 
                      $stmt_article = $user_home->runQuery("SELECT * FROM content 
                      LEFT JOIN menu ON content.cat_id=menu.c_id 
                      LEFT JOIN images ON content.id=images.id_article
                      LEFT JOIN tbl_users on content.member_id = tbl_users.userID
                      ORDER BY content.id DESC LIMIT 20");
                      $stmt_article->execute();

                      while ($rs_article = $stmt_article->fetch(PDO::FETCH_ASSOC)){
                ?>
                <li class="item">
                  <div class="product-img">
                   
                   <?php  if($rs_article['photo']!=null or $rs_article['photo']!=""){echo '<img src="img/uploads/'.$rs_article['photo'].'" alt="Product Image">';}else{echo '<img src="img/default-50x50.gif" alt="Product Image">';} ?>
                    
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title"><?php echo $rs_article['text_title'];?>
                     <?php 
                       if ($rs_article['display']==1) {
                        echo'<span class="label label-info pull-right">published</span>';
                       }else{
                          echo'<span class="label label-danger pull-right">unpublished</span>';
                       }
                      ?>
                      </a>
                        <span class="product-description">
                          Date:&nbsp;<?php echo $rs_article['modified_date'];?>,&nbsp;&nbsp;
                          Categories:&nbsp; <?php echo $rs_article['c_title'];?>,&nbsp;&nbsp;
                          Display:&nbsp; <?php echo $rs_article['displayName'];?>
                        </span>
                  </div>
                </li>
                <!-- /.item -->
                <?php } ?>
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="kd-admin.php?page=content_list" class="uppercase">View all article</a>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->

        </div><!--/col-->
        
      </div><!--/row-->
