<?php
if(isset($_POST['btn-signup']))
{
  $uname = trim($_POST['txtuname']);
  $email = trim($_POST['txtemail']);
  $upass = trim($_POST['txtpass']);
  $code = md5(uniqid(rand()));
  $fname = trim($_POST['txtfname']);
  $lname = trim($_POST['txtlname']);
  $dname = trim($_POST['txtdname']);
  $position = trim($_POST['txtposition']);
  $ulevel = trim($_POST['txtlevel']);
  $uphoto = $_FILES["txtuphoto"]["name"];

  if($uname=="") {
        $error[] = "provide username !"; 
     }
     else if($email=="") {
        $error[] = "provide email id !"; 
     }
     else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = 'Please enter a valid email address !';
     }
     else if($upass=="") {
        $error[] = "provide password !";
     }
     else if(strlen($upass) < 6){
        $error[] = "Password must be atleast 6 characters"; 
     }
     else
     {


        $stmt = $user_home->runQuery("SELECT * FROM tbl_users WHERE userEmail=:email_id");
        $stmt->execute(array(":email_id"=>$email));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($stmt->rowCount() > 0)
        {
          $error[] = "
                <div class='alert alert-error'>
              <button class='close' data-dismiss='alert'>&times;</button>
                <strong>Sorry !</strong>  email allready exists , Please Try another one
              </div>
              ";
        }
        else
        {
          if($user_home->register($email, $uname, $upass,$code,$fname, $lname, $dname, $position, $ulevel, $uphoto))
          { 
            
             /* Move thumnail image */
            $target_dir_thumnail = 'img/user/';
            $img_smg_s=uploadImage($target_dir_thumnail, 'txtuphoto');    
            
            $id = $user_home->lasdID();    
            $key = base64_encode($id);
            $id = $key;
            
            $message = "          
                  Hello $uname,
                  <br /><br />
                  Welcome to keiladialy!<br/>
                  To complete your registration  please , just click following link<br/>
                  <br /><br />
                  <a href='http://localhost/keiladialy/project/keiladialy/admin/verify.php?id=$id&code=$code'>Click HERE to Activate :)</a>
                  <br /><br />
                  Thanks,";
                  
            $subject = "Confirm Registration";
                  
            $user_home->send_mail($email,$message,$subject); 
            
            $msg ="<div class='alert alert-success'>
                  <button class='close' data-dismiss='alert'>&times;</button>
                  <strong>Success!</strong>  We've sent an email to $email.
                          Please click on the confirmation link in the email to create your account. 
                </div>";
          }
          else
          {
            echo "sorry , Query could no execute...";
          }   
        }
      }// end validation

}elseif(@$_REQUEST['action']=='edit') {
  
  $stmt_user = $user_home->runQuery("SELECT * FROM tbl_users WHERE userID=:uid");
  $stmt_user->execute(array(":uid"=>$_REQUEST['uid']));
  $row_user = $stmt_user->fetch(PDO::FETCH_ASSOC);

   if(@$_REQUEST["update"]=="success"){    

      $msg = "
          <div class='alert alert-success'>
            <button class='close' data-dismiss='alert'>&times;</button>
            <strong>Success!</strong> Data have been update! <br>
            ".@$_REQUEST['e_alert']."
            </div>
          ";
    } elseif(@$_REQUEST["update"]=="email"){  
         $msg = "
           <div class='alert alert-error'>
              <button class='close' data-dismiss='alert'>&times;</button>
                <strong>Sorry !</strong>  email allready exists , Please Try another one
              </div>
          ";
    }




}elseif(isset($_REQUEST['btn-update']) and $_REQUEST['action']=='update'){
 
    // Paramater
    $uname = trim($_POST['txtuname']);
    $fname = trim($_POST['txtfname']);
    $lname = trim($_POST['txtlname']);
    $dname = trim($_POST['txtdname']);
    $email = trim($_POST['txtemail']);
    $position = trim($_POST['txtposition']);
    $ulevel = trim($_POST['txtlevel']);
    $uphoto = $_FILES["txtuphoto"]["name"];
    $uid = trim($_POST['txtuid']);
    $code = trim($_POST['code']);


    if ($_REQUEST['vemail']!=$email){
        $stmt = $user_home->runQuery("SELECT * FROM tbl_users WHERE userEmail=:email_id");
        $stmt->execute(array(":email_id"=>$email));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($stmt->rowCount() > 0)
        {
          $error[] = "
                <div class='alert alert-error'>
              <button class='close' data-dismiss='alert'>&times;</button>
                <strong>Can't Update!</strong>  email allready exists , Please Try another one
              </div>
              ";
              echo '<script type="text/javascript">window.location = "kd-admin.php?page=user&action=edit&uid='.$uid.'&update=email"</script>';

        }else{


            $id = $uid;    
            $key = base64_encode($id);
            $id = $key;
         
            $subject='Confirm E-mail Changed';
            $message = "          
                  Hello $uname,
                  <br /><br />
                  Welcome to keiladialy!<br/>
                  To complete your registration  please , just click following link<br/>
                  <br /><br />
                  <a href='http://localhost/keiladialy/project/keiladialy/admin/verify.php?id=$id&code=$code'>Click HERE to Activate :)</a>
                  <br /><br />
                  Thanks,";

            $user_home->send_mail($email,$message,$subject); 

            $stmt_uemail=$user_home->runQuery("UPDATE tbl_users SET userEmail = :uemail, userStatus = :activate WHERE userID =:uid");
            $stmt_uemail->execute(array(':uemail' => $email, ':uid' => $uid, ':activate' => 'N'));

            $e_alert = "Please check E-mail to activate your E-mail";
        }
    }

  if (!empty($_FILES["txtuphoto"]["name"])) {

    $target_dir_thumnail = 'img/user/';
     /* Move thumnail image */
    $img_smg_s=uploadImage($target_dir_thumnail, 'txtuphoto');

    //update file image
    $stmt_user=$user_home->runQuery("UPDATE tbl_users SET firstName=:fname, LastName=:lname, displayName=:dname, position=:upositon, level=:ulevel, userPhoto=:uphoto WHERE userID =:uid");
    $stmt_user->execute(array(':fname' =>$fname, ':lname'=> $lname, ":dname" => $dname, ":upositon" => $position, ":ulevel"=>$ulevel, 
    ":uphoto"=>$uphoto, "uid"=> $uid));
     echo '<script type="text/javascript">window.location = "kd-admin.php?page=user&action=edit&uid='.$uid.'&update=success&e_alert='.@$e_alert.'"</script>';

  }else{

    //No file images
    $stmt_user=$user_home->runQuery("UPDATE tbl_users SET firstName=:fname, LastName=:lname, displayName=:dname, position=:upositon, level=:ulevel WHERE userID =:uid");
    $stmt_user->execute(array(':fname' =>$fname, ':lname'=> $lname, ":dname" => $dname, ":upositon" => $position, ":ulevel"=>$ulevel, "uid"=> $uid));
    echo '<script type="text/javascript">window.location = "kd-admin.php?page=user&action=edit&uid='.$uid.'&update=success&e_alert='.@$e_alert.'"</script>';
  }


}
?>
<div class="row">
  <!-- left column -->
  <div class="col-md-6">
    <!-- general form elements -->
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-fw fa-user-plus"></i> User Register</h3>
         <div class="box-tools pull-right">
           <a href="kd-admin.php?page=user_list" class="btn btn-block btn-primary btn-sm">View all user </a>
          </div>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <div style="padding-top:10px;">
          <div class="col-md-12">
            <?php
            if(isset($error))
            {
               foreach($error as $error)
               {
                  ?>
                  <div class="alert alert-danger">
                      <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                  </div>
                  <?php
               }
            }
            else
            {
                 echo @$msg;
            }
            ?>
          </div>

        </div>
      
      <form role="form" method="post" enctype="multipart/form-data" action="<?php if(isset($_REQUEST['action'])){ echo 'kd-admin.php?page=user&action=update';}else{ echo "kd-admin.php?page=user"; }?>">
        <div class="box-body">
           <div class="form-group">
            <label for="exampleInputEmail1">First Name</label>
            <input type="text" class="form-control"  name="txtfname" value="<?php echo @$row_user['firstName'];?>" placeholder="Enter first name" >
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Last Name</label>
            <input type="text" class="form-control"  name="txtlname" placeholder="Enter last name" value="<?php echo @$row_user['LastName'];?>">
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">User Name</label>
             <input type="text" class="form-control" name="txtuname"  placeholder="Enter user name" value="<?php echo @$row_user['userName'];?>"  required />
          </div>
           <div class="form-group">
            <label for="exampleInputEmail1">Display Name</label>
            <input type="text" class="form-control"  name="txtdname" placeholder="Enter Display Name" value="<?php echo @$row_user['displayName'];?>">
          </div>
            <div class="form-group">
            <label for="exampleInputEmail1">Position</label>
            <input type="text" class="form-control" name="txtposition" placeholder="Enter position" value="<?php echo @$row_user['position'];?>">
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
               <input type="email" class="form-control" id="email" placeholder="Email address" name="txtemail" value="<?php echo @$row_user['userEmail'];?>" required />
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Password" value="<?php echo @$row_user['userPass'];?>" name="txtpass" <?php if(isset($_REQUEST['action'])){?> disabled="disabled"<?php } ?> required />
          </div>
         
          <div class="form-group">
                  <label>User Level</label>
                  <select class="form-control" name="txtlevel">
                    <?php if(@$row_user['level']==1){?>
                    <option value="1" selected="selected">supper Admin</option>
                    <option value="2">Admin</option>
                    <option value="3">user</option>
                    <?php }elseif (@$row_user['level']==2) {?>
                      <option value="1">supper Admin</option>
                      <option value="2" selected="selected">Admin</option>
                      <option value="3">user</option>
                     <?php }elseif (@$row_user['level']==3) {?>
                      
                      <option value="1">supper Admin</option>
                      <option value="2">Admin</option>
                      <option value="3" selected="selected">user</option>

                    <?php }else{ ?>

                        <option value="1">supper Admin</option>
                      <option value="2">Admin</option>
                      <option value="3">user</option>
                      <?php } ?>
                  </select>
                </div>
          <div class="form-group">
            <label for="exampleInputFile">File input</label>
            <input type="file" name="txtuphoto">
              <?php if (@$row_user['userPhoto']!=null){ ?>
                    <img src="img/user/<?php echo $row_user['userPhoto']; ?>" style="width:200px; margin-top:10px;">
                <?php 
                  }else{
                ?>
                  
            <p class="help-block">Upload you image profile.</p>
            <?php } ?>
          </div>
          <!--
          <div class="checkbox">
            <label>
              <input type="checkbox"> disactivate user
            </label>
          </div>
          -->
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
        
          <input type="hidden" value="<?php echo @$row_user['tokenCode']; ?>" name="code">
          <input type="hidden" value="<?php echo $_REQUEST["uid"]; ?>" name="txtuid">
          <input type="hidden" value="<?php echo @$row_user['userEmail'];?>" name="vemail">
          <button type="submit" class="btn btn-primary" 
          name="<?php if (isset($_REQUEST['action'])){echo "btn-update";}else{echo 'btn-signup';}?>">Sign Up</button>
        </div>
      </form>
    </div>
    <!-- /.box -->
  </div><!--./ col -->
<div class="row"><!--/row-->




