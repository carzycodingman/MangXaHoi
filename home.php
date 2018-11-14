<?php 
require_once 'init.php';
require_once 'functions.php';
  // Xử lý logic ở đây
$page = "home";
$postImages = findAllPost($currentUser['id']);
if(isset($_POST['post'])||isset($_FILES['fileToUpload']))
   {
      $content = $_POST['post'];
      $image = $_FILES['fileToUpload'];
      $nameImage = $image['name'];
      $sizeImage = $image['size'];
      $tempImage = $image['tmp_name'];
      $strSql = "INSERT INTO post_images (name_image,content,userid,uploaded_on) VALUES('".$nameImage."','".$content."','".$currentUser['id']."',NOW())";
      $check = uploadPost($nameImage,$sizeImage,$tempImage,$content,$currentUser['id'],$currentUser['email']."/Uploads/",$strSql);
      header("Location: home.php");
  }
?>
<?php include 'header.php'; ?>
<?php if($currentUser):?>
<div id="middle" >
        <div id="ds_mon_hoc" style="left: 110px;bottom: 343px;" class="shadow-none p-3 mb-5 bg-light rounded border">
        <div>
         <form action="home.php" method="post" enctype="multipart/form-data" name="frm3">
            <input type="file"  name="fileToUpload" id="fileToUpload" style="display: none;">
            <div id="sharePost">
                <div id="selectImage" onclick="document.getElementById('fileToUpload').click();">
                    <span>Ảnh</span>
                    <img src="./icon/picture.png"style="height: 25px;width: 25px;">
                </div>
                <textarea  placeholder="Bạn đang nghĩ gì vậy <?php echo $currentUser['fullname'];?> ?" rows = "6" cols = "50" style="overflow: hidden;" name="post" id="textbox"></textarea>
                <div id="submitShare">
                    <input  style = "width: 75px;"type="submit" value="Share" name="submit" class="btn btn-primary">
                </div>
            </div>
         </form>
         </div>
            <?php foreach($postImages as $image):?>
                <?php if($image['content']!=NULL&&$image['name_image']!=NULL):?>
                    <div id="post" class="dsmh_mon_hoc" style="line-height:8px;margin: 15px 15px;">
                      <p><strong id="nameUser"><?php echo $currentUser['fullname'];?></strong></p>
                      <p id="timeUpload" class="ten_truong glyphicon glyphicon-briefcase can_le_icon"><?php echo $image['uploaded_on'];?></p>
                      <p style="top: 76px;" class="dsmh_detail"><?php echo $image['content'];?></p>
                      <img id="imageShow"src="Users/<?php echo $currentUser['email']; ?>/Uploads/<?php echo $image['name_image']; ?>"/>
                    </div>
                <?php else: ?>
                  <?php if($image['name_image']==NULL&&$image['content']!=NULL): ?>
                    <div class="dsmh_mon_hoc" style="line-height:15px;margin: 15px 15px;height: auto;">
                      <p><strong style="    color: hsla(240, 100%, 27%,0.5);position: relative;left: 15px;top: 10px;"><?php echo $currentUser['fullname'];?></strong></p><br/>
                      <p class="ten_truong glyphicon glyphicon-briefcase can_le_icon"><?php echo $image['uploaded_on'];?></p><br/>
                      <div style="width: inherit;">
                      <p style="position:relative;left:0px;top:0px;" class="dsmh_detail"><?php echo $image['content'];?></p>
                    </div>
                    </div>
                    <?php else:?>
                      <div id="post" class="dsmh_mon_hoc" style="line-height:8px;margin: 15px 15px;">
                        <p><strong id="nameUser"><?php echo $currentUser['fullname'];?></strong></p>
                        <p id="timeUpload" class="ten_truong glyphicon glyphicon-briefcase can_le_icon"><?php echo $image['uploaded_on'];?></p>
                        <img id="imageShow"src="Users/<?php echo $currentUser['email']; ?>/Uploads/<?php echo $image['name_image']; ?>"/>
                      </div>
                    <?php endif;?>      
                <?php endif;?>
              <?php endforeach;?>
              </div>
              </div>  <?php else:?>
 	<?php header('Location:index.php');?>
 <?php endif;?>
<?php include 'footer.php'; ?>