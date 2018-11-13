
<?php 
  require_once 'init.php';
  require_once 'functions.php';
  // Xử lý logic ở đây
   //kiểm tra có phai là file image hay không   
   $postImages = findAllImagesById($currentUser['id']);
   $posts  = findAllPostsById($currentUser['id']);
   $profile = findProfileById($currentUser['id']);
   if(isset($_FILES['uploadsProfile']))
   {
        $imageP = $_FILES['uploadsProfile'];
        $nameImage = $imageP['name'];
        $sizeImage = $imageP['size'];
        $tempImage = $imageP['tmp_name'];        $strSql ="UPDATE  profile 
                  SET profile_cover = '".$nameImage."'
                  WHERE userid = '".$currentUser['id']."'";
        $check = uploadImage($nameImage,$sizeImage,$tempImage,$currentUser['email']."/Profile/",$strSql);   
        resizeImage("Users/".$currentUser['email']."/Profile/".$nameImage,500,500,false,
                    "Users/".$currentUser['email']."/Profile/".$nameImage);
       header('Location:index.php');
    }

   if(isset($_FILES['uploadsCoverImage']))
   {
        $imageC = $_FILES['uploadsCoverImage'];
        $nameImage = $imageC['name'];
        $sizeImage = $imageC['size'];
        $tempImage = $imageC['tmp_name'];
        $strSql = "UPDATE  profile 
                   SET header_cover = '".$nameImage."'                   
                   WHERE userid = '".$currentUser['id']."' ";
        $check = uploadImage($nameImage,$sizeImage,$tempImage,$currentUser['email']."/Profile/",$strSql);
        resizeImage("Users/".$currentUser['email']."/Profile/".$nameImage,500,500,false,
                    "Users/".$currentUser['email']."/Profile/".$nameImage);
        header('Location:index.php');
   }

   if(isset($_POST['post']))
   {
      $content = $_POST['post'];
      $postid = null;
      if(!empty($content))
      {
        UploadPost($content,$currentUser['id']);
        $postid = MaxIdPost();
        if($postid)
            $check1 = 1;
      }
      if(isset($_FILES['fileToUpload'])&&!is_null($_FILES['fileToUpload']))
      {
        $image = $_FILES['fileToUpload'];
        $nameImage = $image['name'];
        $sizeImage = $image['size'];
        $tempImage = $image['tmp_name'];
        $strSql = "INSERT INTO post_images (name_image,userid,postid,uploaded_on) VALUES('".$nameImage."','".$currentUser['id']."','".$postid."',NOW())";
        $check = uploadImage($nameImage,$sizeImage,$tempImage,$currentUser['email']."/Uploads/",$strSql);
        resizeImage("Users/".$currentUser['email']."/Uploads/".$nameImage,500,500,false,
                    "Users/".$currentUser['email']."/Uploads/".$nameImage);
      }
         header('Location:index.php');
  }
?>
<?php include 'header.php'; ?>
	<?php if($currentUser):?>      
      <div id="middle" >

        <div id="header" class=" shadow-none p-3 mb-5 bg-light rounded border" style="background-image: url(Users/<?php echo $currentUser['email']; ?>/Profile/<?php echo $profile['header_cover']; ?>) !important;">
             <form action="index.php" method="post" enctype="multipart/form-data" id="frm2" name="frm2">
                <input type="file"  name="uploadsCoverImage" id="uploadsCoverImage" style="display: none;" onchange="submitForm('frm2')">
            </form>           
            <div id="uploadsCover" onclick="document.getElementById('uploadsCoverImage').click();">
                <img src="icon/camera.png">
                <span><strong>Cập nhật ảnh bìa</strong></span>
            </div>    
            <form action="index.php" method="post" enctype="multipart/form-data" name="frm1" id="frm1">
                <input type="file"  name="uploadsProfile" id="uploadsProfile" style="display: none;" onchange="submitForm('frm1')">
            </form>

            <div id="profile_cover" style="background-image: url(Users/<?php echo $currentUser['email']; ?>/Profile/<?php echo $profile['profile_cover']; ?>);">
            </div>
            <div  id="selectProfile" onclick="document.getElementById('uploadsProfile').click();">
                <img src="icon/camera.png">
                <span><strong>Cập nhật</strong></span>            
            </div>
            <div id="user_name" class="position_sector">
                <p id="name"><?php echo $currentUser['fullname']?></p>
            </div>
            <?php if($currentUser['nickname']!=0):?>
              <div id="nick_name" class="position_sector"><?php echo $currentUser['nickname']?></div>
            <?php endif;?>
            <div id="trong" class="option_header background_sector position_sector">

            </div>
            <div id="dong_thoi_gian" class="option_header background_sector position_sector">
                <span>Dòng thời gian</span>
            </div>
            <div id="gioi_thieu_layout" class="option_header background_sector position_sector">
                <span>Giới thiệu</span>
            </div>
            <div id="ban_be" class="option_header background_sector position_sector">
                <span>Bạn bè</span>
            </div>
            <div id="anh" class="option_header background_sector position_sector">
                <span>Ảnh</span>
            </div>
            <div id="luu_tru" class="option_header background_sector position_sector">
                <span>Lưu trữ</span>
            </div>
            <div id="xem_them" class="option_header background_sector position_sector">
                <span>Xem thêm</span>
            </div>
        </div>
        <div id="gioi_thieu" class="background_sector shadow-none p-3 mb-5 bg-light rounded border">
            <div id="gt_header">
                <span id="qua_dat" class="glyphicon glyphicon-globe"></span>
                <span>Giới thiệu</span>
            </div>
            <hr style="    position: absolute;left: 20px;top: 45px;width: 290px;">
            <div id="gt_content" class="mb-0 ">
                <div class="gt_format">
                    <span class="glyphicon glyphicon-briefcase can_le_icon"></span>
                    <p>
                        Sinh viên tại
                        <a href="https://www.facebook.com/hcmus.edu.vn/?timeline_context_item_type=intro_card_work&timeline_context_item_source=100022266171596&fref=tag&rf=1396475480643432">
                            <?php echo $profile['university'];?>
                        </a>
                    </p>
                </div>
                <div class="gt_format">
                    <span class="glyphicon glyphicon-education can_le_icon"></span>
                    <p>
                        Học CĐ Công Nghệ Thông Tin
                        <a href="https://www.facebook.com/hcmus.edu.vn/?timeline_context_item_type=intro_card_work&timeline_context_item_source=100022266171596&fref=tag&rf=1396475480643432">
                            Đại học Khoa học Tự nhiên
                        </a>
                    </p>
                </div>
                <div class="gt_format">
                    <span class="glyphicon glyphicon-education can_le_icon"></span>
                    <p>
                        Đã học tại
                        <a href="https://www.facebook.com/pages/Le-Minh-Xuan-high-school-Saigon-Vietnam/1424209467798778?timeline_context_item_type=intro_card_education&timeline_context_item_source=100022266171596&fref=tag">
                            <?php echo $profile['highschool'];?>
                        </a>
                    </p>
                </div>
                <div class="gt_format">
                    <span class="glyphicon glyphicon-envelope can_le_icon"></span>
                    <p>
                        Email
                        <a href="https://accounts.google.com/signin/v2/identifier?passive=1209600&osid=1&continue=https%3A%2F%2Fcontacts.google.com%2F&followup=https%3A%2F%2Fcontacts.google.com%2F&flowName=GlifWebSignIn&flowEntry=ServiceLogin">
                            <?php echo $currentUser['email'];?>

                        </a>
                    </p>
                </div>
                <div class="gt_format">
                    <span class="glyphicon glyphicon-baby-formula can_le_icon"></span>
                    <p>Ngày sinh <a><?php echo $profile['birthday'];?></a></p>
                </div>
                <div class="gt_format">
                    <span class="glyphicon glyphicon-map-marker can_le_icon"></span>
                    <p>Đến từ <a><?php echo $profile['placeofbirth'];?></a></p>
                </div>
                <div class="gt_format">
                    <span class="glyphicon glyphicon-home can_le_icon"></span>
                    <p>Sống tại <a><?php echo $profile['currentresidence'];?></a></p>
                </div>
            </div>
        </div>
        
        <div id="ds_mon_hoc" class="shadow-none p-3 mb-5 bg-light rounded border">
        
        <div>
         <form action="index.php" method="post" enctype="multipart/form-data" name="frm3">
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
                <?php if($image['postid']!=0):?>
                    <div id="post" class="dsmh_mon_hoc" style="line-height:8px;margin: 15px 15px;">
                      <p><strong id="nameUser"><?php echo $currentUser['fullname'];?></strong></p>
                      <p id="timeUpload" class="ten_truong glyphicon glyphicon-briefcase can_le_icon"><?php echo $image['uploaded_on'];?></p>
                      <p style="top: 76px;" class="dsmh_detail"><?php echo $image['content'];?></p>
                      <img id="imageShow"src="Users/<?php echo $currentUser['email']; ?>/Uploads/<?php echo $image['name_image']; ?>"/>
                    </div>
                <?php else: ?>
                    <div class="dsmh_mon_hoc" style="line-height:8px;margin: 15px 15px;">
                      <p><strong style="color: hsla(240, 100%, 27%,0.5);"><?php echo $currentUser['fullname'];?></strong></p>
                      <p class="ten_truong glyphicon glyphicon-briefcase can_le_icon"><?php echo $image['uploaded_on'];?></p>
                      <img id="imageShow" src="Users/<?php echo $currentUser['email']; ?>/Uploads/<?php echo $image['name_image']; ?>"/>
                    </div>
                <?php endif;?>
              <?php endforeach;?> 
        </div>
      <?php else: ?>
      Chào mừng bạn đến với mạng xã hội ...	
	   <?php  	endif; ?>
       <script type="text/javascript">
       </script>
<?php include 'footer.php'; ?>