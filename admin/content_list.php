
 <div class="box">
  <div class="box-header">
    <h3 class="box-title" style="padding-right:20px;">MENU List </h3>
    <a href="sr-admin.php?page=content" class="btn btn-primary btn-sm"> Add new menu</a>
    <div class="box-tools">
      <div class="input-group" style="width: 150px;">

        <input type="text" name="table_search" class="form-control input-sm pull-right" placeholder="Search">
        <div class="input-group-btn">
          <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
        </div>
      </div>
    </div>
  </div><!-- /.box-header -->
  <div class="box-body table-responsive">
    
    <?php
	

	
	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	$query = $user_home->runQuery("SELECT COUNT(*) as num FROM content 
				LEFT JOIN menu ON content.cat_id=menu.c_id 
				LEFT JOIN images ON content.id=images.id_article
				LEFT JOIN tbl_users on content.member_id = tbl_users.userID
				ORDER BY content.id DESC");
    $query->execute();
	$total_pages = $query -> fetch(PDO::FETCH_ASSOC);
	$total_pages = $total_pages['num'];

	
	/* Setup vars for query. */
	$targetpage = "kd-admin.php?page=content"; 	//your file name  (the name of this file)
	$limit = 15; 								//how many items to show per page
	$page =@$_GET['page'];
	if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0
	
	/* Get data. */
	$result=$user_home->runQuery("SELECT content.id, content.text_title, content.photo, content.modified_date,  content.display, content.sort, menu.c_id,  menu.c_title, images.img_thumbnail, tbl_users.userName FROM content 
				LEFT JOIN menu ON content.cat_id=menu.c_id 
				LEFT JOIN images ON content.id=images.id_article
				LEFT JOIN tbl_users on content.member_id = tbl_users.userID
				ORDER BY content.id DESC LIMIT 0, 15");
    $result->execute(array());
     
          
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;						//last page minus 1
	
	/* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"pagination\">";
		//previous button
		if ($page > 1) 
			$pagination.= "<a href=\"$targetpage&page=$prev\">Previous</a>";
		else
			$pagination.= "<span class=\"disabled\">Previous</span>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a href=\"$targetpage&page=$counter\">$counter</a>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"$targetpage&page=1\">1</a>";
				$pagination.= "<a href=\"$targetpage&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"$targetpage&page=1\">1</a>";
				$pagination.= "<a href=\"$targetpage&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a href=\"$targetpage&page=$next\">Next</a>";
		else
			$pagination.= "<span class=\"disabled\">Next</span>";
		$pagination.= "</div>\n";		
	}
?>
                                    <table class="table table-hover">
                                        <tr>
                                            <th>No</th>
                                            <th>Images</th>
                                            <th>Title </th>
                                            <th>User</th>
                                            <th colspan="2">Action</th>

                                        </tr>
                                  		<?php
											$i=1;


								
                               while($row=$result->fetch(PDO::FETCH_ASSOC)){
                                       ?>
                 <tr>
                                                <td><?php echo $i ?></td>
                                                <td>  <?php if(!empty($result_article['img_thumbnail'])){
        echo '<img src="img/uploads/thumbs/'.$result_article['img_thumbnail'].'"/>';
		}else{echo '<img src="img/default-50x50.gif" alt="Product Image">';} ?></td>
                                                 <td>
                                                    <h4><?php echo $row['text_title'];?></h4>
                                                    <span class="help-block">
                                                        Date:&nbsp;<?php echo $row['modified_date'];?>,&nbsp;&nbsp;&nbsp;&nbsp;

                                                        Categories:&nbsp; <?php echo $row['c_title'];?>,&nbsp;&nbsp;&nbsp;&nbsp;
                                                      
                                                        Display:&nbsp; <?php if($row['display']==0) echo '<span style="color:red;">Unshowed</span>'; else echo 'Showed';?>

                                                        
                                                        </span>
                                                 </td>
                                                <td><?php echo $row['userName'];?></td>

                                                <td>
                                                    <i class="fa fa-fw fa-times"></i>
                                                    <a href="admin/content_delete.php?id_article=<?php echo $row['id']; ?>">
                                                    Delete
                                                    </a>
                                                </td>
                                                <td>
                                                    <i class="fa fa-fw fa-pencil-square-o"></i>
                                                    <a href="kd-admin.php?page=content&action=edit&id_article=<?php echo $row['id']; ?>">
                                                    Edit
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php
                                        $i++;

                               }?>



                                    </table>
                                
                              


  </div><!-- /.box-body -->
  <div class="box-footer clearfix">
    <ul class="pagination pagination-sm no-margin pull-right">
      <?php echo $pagination; ?>
    </ul>
  </div>
</div><!-- /.box -->
