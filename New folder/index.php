<?php 
  require_once 'init.php';
  require_once 'functions.php';
  // Xử lý logic ở đây
   //kiểm tra có phai là file image hay không
  $check1 = 0;   
   $postImages = findAllImagesById($currentUser['id']);
   $posts  = findAllPostsById($currentUser['id']);
   if(isset($_POST['submit'])&&isset($_POST['post']))
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
      if(isset($_FILES['fileToUpload']))
      {
        $image = $_FILES['fileToUpload'];
        $nameImage = $image['name'];
        $sizeImage = $image['size'];
        $tempImage = $image['tmp_name'];
        $check = uploadImage($nameImage,$sizeImage,$tempImage,$currentUser['id'],$postid);
      }
   }
?>
	
<?php include 'header.php'; ?>
	<?php if($currentUser):?>      
      <div id="middle" >
        <div id="header" class=" shadow-none p-3 mb-5 bg-light rounded border">
            <div id="profile_cover" class="position_sector">
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
                            Trường Đại học Khoa học Tự nhiên,Đại học Quốc gia Thành Phố Hồ Chí Minh
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
                            Lê Minh Xuân high school, Sai gon, Viet Nam
                        </a>
                    </p>
                </div>
                <div class="gt_format">
                    <span class="glyphicon glyphicon-envelope can_le_icon"></span>
                    <p>
                        Email
                        <a href="https://accounts.google.com/signin/v2/identifier?passive=1209600&osid=1&continue=https%3A%2F%2Fcontacts.google.com%2F&followup=https%3A%2F%2Fcontacts.google.com%2F&flowName=GlifWebSignIn&flowEntry=ServiceLogin">
                            tanhro966@gmail.com
                        </a>
                    </p>
                </div>
                <div class="gt_format">
                    <span class="glyphicon glyphicon-baby-formula can_le_icon"></span>
                    <p>Ngày sinh <a>15/7/1998</a></p>
                </div>
                <div class="gt_format">
                    <span class="glyphicon glyphicon-map-marker can_le_icon"></span>
                    <p>Đến từ <a>Bình Phước, Việt Nam</a></p>
                </div>
                <div class="gt_format">
                    <span class="glyphicon glyphicon-home can_le_icon"></span>
                    <p>Sống tại <a>Thành Phố Hồ Chí Minh</a></p>
                </div>
            </div>
        </div>
        <div id="lien_lac" class="background_sector alert alert-dark shadow-none p-3 mb-5 bg-light rounded border ">
            <p id="lh_header" class="lien_he list-group-item list-group-item-action active ">Yêu thích</p>
            <p class="lien_he ">
                <a href="http://nguyenphucloi.epizy.com/1660321.html" class="list-group-item list-group-item-action" target="_blank">
                    1 Nguyễn Phúc Lợi
                </a>
            </p>
            <p class="lien_he">
                <a href="https://nghiiatran.000webhostapp.com/1660370-NghiiaTran.html" class="list-group-item list-group-item-action" target="_blank">
                    2 Nghĩa Trần
                </a>
            </p>
            <p class="lien_he">
                <a href="https://trongvx.000webhostapp.com/1660655.html" class="list-group-item list-group-item-action" target="_blank">
                    3 Võ Xuân Trọng
                </a>
            </p>
            <p class="lien_he">
                <a href="https://tuevo.000webhostapp.com/1660691.html" class="list-group-item list-group-item-action" target="_blank">
                    4 Tuệ Võ
                </a>
            </p>
            <p class="lien_he">
                <a href="https://khonemhoanggia.000webhostapp.com/1660721.html" class="list-group-item list-group-item-action" target="_blank">
                    5 Trần Quang Vinh
                </a>
            </p>
            <p class="lien_he">
                <a href="http://test472.coolpage.biz/1660472.html" class="list-group-item list-group-item-action" target="_blank">
                    6 Nguyễn Minh Quang
                </a>
            </p>
            <p class="lien_he">
                <a href="https://suonheobay.000webhostapp.com/1660357.html" class="list-group-item list-group-item-action" target="_blank">
                    7 Trương Phương Hoài Nam
                </a>
            </p>
            <p class="lien_he">
                <a href="https://tkloc.000webhostapp.com/1660317.html" class="list-group-item list-group-item-action" target="_blank">
                    8 Trần Kim Lộc
                </a>
            </p>
            <p class="lien_he">
                <a href="http://1660372.epizy.com/1660372.html?i=2" class="list-group-item list-group-item-action" target="_blank">
                    9 Nguyễn Hữu Nghĩa
                </a>
            </p>
            <p class="lien_he">
                <a href="https://laduylocit.000webhostapp.com/1660318" class="list-group-item list-group-item-action" target="_blank">
                    10 Lã Duy Lộc
                </a>
            </p>
        </div>
        <div id="ds_mon_hoc" class="shadow-none p-3 mb-5 bg-light rounded border">
            <p id="dsmh_header">Tạo bài viết</p>
            <div>
         <form action="index.php" method="post" enctype="multipart/form-data">
            <input type="file"  name="fileToUpload" id="fileToUpload" style="display: none;">
            <div class="selectImage">
               <img src="./icon/picture.png"style="height: 25px;width: 25px;"  onclick="document.getElementById('fileToUpload').click();">
            </div>
            <input type="text" name="post">
            <input type="submit" value="Share" name="submit" id ="sharePost"class="btn btn-primary">
            <?php echo $check1;  ?>
         </form>      </div>
            
            <div class="dsmh_mon_hoc">
              <?php foreach($posts as $post):?>
                <?php foreach($postImages as $image):?>
                  <?php if($post['imageid'] == $image['id'] &&
                           $post['createdat']!==$image['uploaded_on']):?>
                    <div style="line-height:8px;margin: 15px 15px;">
                      <p><strong style="color: hsla(240, 100%, 27%,0.5);"><?php echo $currentUser['fullname'];?></strong></p>
                      <p class="ten_truong glyphicon glyphicon-briefcase can_le_icon"><?php echo $post['createdat'];?></p>
                      <p style="top: 76px;" class="dsmh_detail"><?php echo $post['content'];?></p>
                      <img src="uploads/<?php echo $$image['name_image']; ?>"/>
                    </div>
                    <?php endif;?>
            <?php endforeach;?>
             <?php if($post['imageid'] == 0):?>
                      <div style="line-height:8px;margin: 15px 15px;">
                        <p><strong style="color: hsla(240, 100%, 27%,0.5);"><?php echo $currentUser['fullname'];?></strong></p>
                        <p class="ten_truong glyphicon glyphicon-briefcase can_le_icon"><?php echo $post['createdat'];?></p>
                        <p style="top: 76px;" class="dsmh_detail"><?php echo $post['content'];?></p>
                      </div>
                      <?php endif;?>
          <?php endforeach; ?>
            </div>
          </div>
        </div>     
      <?php else: ?>
      Chào mừng bạn đến với mạng xã hội ...	
	   <?php  	endif; ?>
<?php include 'footer.php'; ?>