<?php
  require_once 'functions.php';
  function createPersonalPage($fileName)
    {
      $contentFunction ='
      <?php
      function findAllFriend($userid)
    {
      global $db;
      $stmt = $db->prepare("SELECT *
                            FROM (SELECT friend_list.friendid,friend_list.userid 
                                       FROM friend_list 
                                       UNION 
                                       SELECT request_friend.sent_userid,request_friend.received_userid 
                                       FROM request_friend
                                       WHERE request_friend.accepted = 1) AS B
                            LEFT JOIN users ON   users.id  = B.friendid
                            WHERE B.userid = ?");
      $stmt->execute(array($userid));
      $friends = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $friends;
    }
   function resetPassword($strSql)
   {
      global $db;    
      $newPass = $db->query($strSql);
      return $newPass;
   }
   function executeNonQuery($strSql)
   {
     global $db;    
      $newPass = $db->query($strSql);
      return $newPass;
   }
   function findFriend($visitor,$userid)
   {
    global $db;
      $stmt = $db->prepare("SELECT * 
                            FROM (SELECT friend_list.friendid,friend_list.userid 
                                       FROM friend_list 
                                       UNION 
                                       SELECT request_friend.sent_userid,request_friend.received_userid 
                                       FROM request_friend
                                       WHERE request_friend.accepted = 1) AS B
                            WHERE B.friendid=? AND B.userid=?LIMIT 1");
      $stmt->execute(array($visitor,$userid));
      $request = $stmt->fetch(PDO::FETCH_ASSOC);
      return $request;
   }
   function findRequestFriend($visitor,$userid)
   {
     global $db;
      $stmt = $db->prepare("SELECT * 
                            FROM request_friend 
                            WHERE sent_userid=? AND
                            received_userid = ? AND 
                            accepted = 0 LIMIT 1");
      $stmt->execute(array($visitor,$userid));
      $request = $stmt->fetch(PDO::FETCH_ASSOC);
      return $request;
   }
   function markResetPassUsed($secret)
   {
      global $db;
      $stmt = $db->prepare("UPDATE pass_reset 
                            SET pass_reset.used = 1
                            WHERE pass_reset.secret = ?");
      $stmt->execute(array($secret));
      $passReset = $stmt->fetch(PDO::FETCH_ASSOC);
   }
   function findSecretPassword($secret)
   {
      global $db;
      $stmt = $db->prepare("SELECT * FROM pass_reset WHERE secret=? LIMIT 1");
      $stmt->execute(array($secret));
      $passReset = $stmt->fetch(PDO::FETCH_ASSOC);
      return $passReset;
   }
   function findUserById($id){
      global $db;
      $stmt = $db->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
      $stmt->execute(array($id));
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      return $user;
   }
   function findUserByEmail($email){
      global $db;
      $stmt = $db->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
      $stmt->execute(array($email));
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      return $user;
   }
   function findAllPostById($userid)
   {
      global $db;
      $stmt = $db->prepare("SELECT *
                            FROM post_images
                            WHERE post_images.userid = ?
                            ORDER BY post_images.uploaded_on DESC");
      $stmt->execute(array($userid));
      $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $posts;
   }
   function findAllPost($userid)
   {
      global $db;
      $stmt = $db->prepare("SELECT *
                            FROM post_images
                            LEFT JOIN friend_list ON friend_list.friendid = post_images.userid
                            LEFT JOIN users ON users.id = post_images.userid
                            WHERE friend_list.friendid IS NOT NULL OR post_images.userid = ? 
                            ORDER BY post_images.uploaded_on DESC");
      $stmt->execute(array($userid));
      $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $posts;
   }
   function findProfileImage($userid)
   {
      global $db;
      $stmt = $db->prepare("SELECT *
                       FROM profile
                       WHERE profile.userid = ? AND 
                       profile.header_cover IS NULL");
      $stmt->execute(array($userid));
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      return $user;
   }
   function findProfileById($userid)
   {
      global $db;
      $stmt = $db->prepare("SELECT *
                       FROM profile
                       WHERE profile.userid = ? LIMIT 1");
      $stmt->execute(array($userid));
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      return $user;

   }
   function updateProfile($strSql)
   {
      global $db;
      /*$existsProfile = findProfileById($userid);
      if(!$existsProfile)*/    
      $profile = $db->query($strSql);
      return $profile;
   }  
   function test_input($data)
   {
      $data = trim($data);
      $data=stripcslashes($data);
      $data = htmlspecialchars($data);
      return $data;
   }
   function uploadPost($nameImage,$sizeImage,$tempImage,$content,$userid,$dir,$strSql)
   {  
      global $db;
      if($nameImage == NULL&&$content!=NULL)
      {
          $strSql2 = "INSERT INTO post_images (content,userid,uploaded_on) VALUES(\'".$content."\',\'".$userid."\',NOW())";
          $insertImage = $db->query($strSql2);
          return;          
      }
      uploadImage($nameImage,$sizeImage,$tempImage,$dir,$strSql);
      resizeImage("Users/".$dir.$nameImage,500,500,false,
                    "Users/".$dir.$nameImage);
      return;  

   }
   function uploadImage($nameImage,$sizeImage,$tempImage,$dir,$strSql)
   {  
      global $db;
      // Check if image file is a actual image or fake image
      if(!empty($nameImage)) 
      {
         $allowType =  array(\'jpg\',\'png\',\'jpeg\',\'gif\');
         $target_dir = "Users/".$dir;
         $target_file = $target_dir . basename($nameImage);
         $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
         if(in_array($imageFileType, $allowType))
         {
            $check = getimagesize($tempImage);
            if($check !== false)
            {
               move_uploaded_file($tempImage, $target_file);
            }

            $insertImage = $db->query($strSql);
            if($insertImage)
            {
               return 1;
            }
            else
               return 0;
         }
         else
            return -2;
      }
      return -3;
   }
   function deleteDirectory($dirname) {
         if (is_dir($dirname))
           $dir_handle = opendir($dirname);
     if (!$dir_handle)
          return false;
     while($file = readdir($dir_handle)) {
           if ($file != "." && $file != "..") {
                if (!is_dir($dirname."/".$file))
                     unlink($dirname."/".$file);
                else
                     delete_directory($dirname."/".$file);
           }
     }
     closedir($dir_handle);
     rmdir($dirname);
     return true;
}
   function resizeImage($file, $w, $h, $crop=false,$output) 
   {
      list($width, $height) = getimagesize($file);
      $r = $width / $height;
      if ($crop)
      {
         if ($width > $height) 
         {
               $width = ceil($width-($width*abs($r-$w/$h)));
         } 
         else 
         {
            $height = ceil($height-($height*abs($r-$w/$h)));
         }
         $newwidth = $w;
         $newheight = $h;
      } 
      else 
      {
         if ($w/$h > $r) 
         {
               $newwidth = $h*$r;
               $newheight = $h;
         } 
         else 
         {
               $newheight = $w/$r;
               $newwidth = $w;
         }
      }
      $src = imagecreatefromjpeg($file);
      $dst = imagecreatetruecolor($newwidth, $newheight);
      imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
      imagejpeg($dst, $output);
   }
   ?>';
   /*----------------------------------------------------------------------------------------------------*/
      $contentInit ='
  
  <?php
  
  require_once "functions.php";
  $email = "'.$fileName.'";
  $currentUser = false;
  session_start();
  if(isset($_GET[\'ID\']))
    $_SESSION[\'userid\'] = $_GET[\'ID\'];
  else if($currentUser)
    header(\'Location: http://localhost:8080/MangXaHoi/\');
  $personalID = false;
  try
  {
    $db = new PDO("mysql:host=localhost;dbname=btcn06;charset=utf8","root","");
    $user = findUserByEmail($email);
    if(isset($_SESSION[\'userid\']))
    {
      if($user[\'id\'] == (int)$_SESSION[\'userid\'])
        header(\'Location: http://localhost:8080/MangXaHoi/\');
      $currentUser = findUserById((int)$_SESSION[\'userid\']);
    }
    if($user)
    {
      $personalID = $user;
    }
  }
  catch(PDOException $e)
  {
    echo "<h1>Không thể kết nối với server.</h1>";
  }
?>
      
      ';

/*----------------------------------------------------------------------------------------------------*/

      $contentIndex = '
       
        <?php 
  require_once "init.php";
  require_once "functions.php";
  // Xử lý logic ở đây
   //kiểm tra có phai là file image hay không   
  $page = "index";
  $requestSuccess = false;
   $postImages = findAllPostById($personalID["id"]);
   $profile = findProfileById($personalID["id"]);
   $request =  findRequestFriend($currentUser[\'id\'],$personalID[\'id\']);
   $friend = findFriend($currentUser[\'id\'],$personalID[\'id\']);
   $friends = findAllFriend($currentUser[\'id\']);
   //i = 0 > j = cot - 1 > i=dong -1 > j = 0
   if(isset($_POST[\'ketban\']))
    {
      if(!$request&&!$friend)
      {
        $strSql = @"INSERT INTO request_friend(sent_userid,received_userid,requestedon,accepted)
                VALUES (\'".$currentUser["id"]."\',\'".$personalID["id"]."\',NOW(),0)";
        $requestSuccess = executeNonQuery($strSql);
        header(\'Location: index.php\');
      }
      if($friend)
      {
        $strSql = @"DELETE FROM request_friend
                    WHERE  sent_userid = \'".$currentUser[\'id\']."\' AND 
                           received_userid = \'".$personalID[\'id\']."\' AND
                           accepted = 1";
        $strSql1 = @"DELETE FROM friend_list
                    WHERE  friend_list.userid = \'".$personalID[\'id\']."\'";
        executeNonQuery($strSql1);
        $requestSuccess = executeNonQuery($strSql);
        header(\'Location: index.php\');
      }
    
    }
    if(isset($_POST[\'huyyeucauketban\'])&&$request)
    {
      $strSql = @"DELETE FROM request_friend
                 WHERE request_friend.sent_userid = \'".$currentUser[\'id\']."\'";
      $strSql1 = @"DELETE FROM friend_list
                   WHERE friend_list.friendid = \'".$currentUser[\'id\']."\'";
     $requestSuccess = executeNonQuery($strSql);
     header(\'Location: index.php\');
    }

?>
<?php include "header.php"; ?>
  <?php if($personalID):?>    
      <div id="middle"  style="bottom: -87px;left: 0px;">
        <div id="header" class=" shadow-none p-3 mb-5 bg-light rounded border" style="background-image: url(/Profile/<?php echo $profile["header_cover"]; ?>) !important;">
            <div id="profile_cover" style="background-image: url(/Profile/<?php echo $profile["profile_cover"]; ?>);">
            </div>
            <div id="user_name" class="position_sector">
                <p id="name"><?php echo $personalID["fullname"]?></p>
            </div>
            <?php if($profile["nickname"]!=NULL):?>
              <div id="nick_name" class="position_sector">(<?php echo $profile["nickname"]; ?>)</div>
            <?php endif;?>
            <div id="guiketban">
                <form action="index.php" method="POST">      
                  <button type="submit" name="ketban" class="btn btn-primary">
                    <?php if ($request):?>
                        Đã gửi lời mời kết bạn
                    <?php elseif(!$friend):?>
                        Gửi kết bạn
                    <?php else :?>
                        Hủy kết bạn
                    <?php endif;?>
                   </button><br/>
                </form>
            </div>
            <?php if($request):?>
            <div id="guiketban" style="left: 510px">
                <form action="index.php" method="POST">      
                  <button type="submit" style="width: 111px" name="huyyeucauketban" class="btn btn-primary">
                    Hủy yêu cầu
                   </button><br/>
                </form>
            </div>
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
                            <?php echo $profile["university"];?>
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
                            <?php echo $profile["highschool"];?>
                        </a>
                    </p>
                </div>
                <div class="gt_format">
                    <span class="glyphicon glyphicon-envelope can_le_icon"></span>
                    <p>
                        Email
                        <a href="https://accounts.google.com/signin/v2/identifier?passive=1209600&osid=1&continue=https%3A%2F%2Fcontacts.google.com%2F&followup=https%3A%2F%2Fcontacts.google.com%2F&flowName=GlifWebSignIn&flowEntry=ServiceLogin">
                            <?php echo $personalID["email"];?>

                        </a>
                    </p>
                </div>
                <div class="gt_format">
                    <span class="glyphicon glyphicon-baby-formula can_le_icon"></span>
                    <p>Ngày sinh <a><?php echo $profile["birthday"];?></a></p>
                </div>
                <div class="gt_format">
                    <span class="glyphicon glyphicon-map-marker can_le_icon"></span>
                    <p>Đến từ <a><?php echo $profile["placeofbirth"];?></a></p>
                </div>
                <div class="gt_format">
                    <span class="glyphicon glyphicon-home can_le_icon"></span>
                    <p>Sống tại <a><?php echo $profile["currentresidence"];?></a></p>
                </div>
            </div>
        </div>
        <div id="lien_lac" class="background_sector alert alert-dark shadow-none p-3 mb-5 bg-light rounded border ">
            <p id="lh_header" class="lien_he list-group-item list-group-item-action active ">Yêu thích</p>
            <?php foreach($friends as $fr):?>
            <p class="lien_he ">
                <a href="http://localhost:8080/MangXaHoi/Users/<?php echo $fr[\'email\']?>/?ID=<?php echo $currentUser[\'id\'];?> " class="list-group-item list-group-item-action" target="_blank" onclick="">
                    <?php echo $fr[\'fullname\'];?>
                </a>
            </p>
          <?php endforeach;?>
        </div>
        <div id="ds_mon_hoc" class="shadow-none p-3 mb-5 bg-light rounded border">
        
            <?php foreach($postImages as $image):?>
                <?php if($image["content"]!=NULL&&$image["name_image"]!=NULL):?>
                    <div id="post" class="dsmh_mon_hoc" style="line-height:8px;margin: 15px 15px;">
                      <p><strong id="nameUser"><?php echo $personalID["fullname"];?></strong></p>
                      <p id="timeUpload" class="ten_truong glyphicon glyphicon-briefcase can_le_icon"><?php echo $image["uploaded_on"];?></p>
                      <p style="top: 76px;" class="dsmh_detail"><?php echo $image["content"];?></p>
                      <img id="imageShow"src="Uploads/<?php echo $image["name_image"]; ?>"/>
                    </div>
                <?php else: ?>
                  <?php if($image["name_image"]==NULL&&$image["content"]!=NULL): ?>
                    <div class="dsmh_mon_hoc" style="line-height:15px;margin: 15px 15px;height: auto;">
                      <p><strong style="    color: hsla(240, 100%, 27%,0.5);position: relative;left: 15px;top: 10px;"><?php echo $personalID["fullname"];?></strong></p><br/>
                      <p class="ten_truong glyphicon glyphicon-briefcase can_le_icon"><?php echo $image["uploaded_on"];?></p><br/>
                      <div style="width: inherit;">
                      <p style="position:relative;left:0px;top:0px;" class="dsmh_detail"><?php echo $image["content"];?></p>
                    </div>
                    </div>
                    <?php else:?>
                      <div id="post" class="dsmh_mon_hoc" style="line-height:8px;margin: 15px 15px;">
                        <p><strong id="nameUser"><?php echo $personalID["fullname"];?></strong></p>
                        <p id="timeUpload" class="ten_truong glyphicon glyphicon-briefcase can_le_icon"><?php echo $image["uploaded_on"];?></p>
                        <img id="imageShow"src="Uploads/<?php echo $image["name_image"]; ?>"/>
                      </div>
                    <?php endif;?>      
                <?php endif;?>
              <?php endforeach;?>
              </div>
              </div> 
      <?php else: ?>
        <p  style = "position: absolute;top: 90px;">
      Chào mừng bạn đến với mạng xã hội ... 
     </p>
     <?php    endif; ?>
       <script type="text/javascript">
       </script>
<?php include "footer.php"; ?>
      ';
/*----------------------------------------------------------------------------------------------------*/
      $contentHeader ='<?php
  if($page === \'index\')
    {
      if(!$personalID)
          echo \'<style type"text/css">
              #navheader{
                left:800px; 
                }
              </style>\';
         else
          echo \'<style type"text/css">
              #navheader{
                left:755px; 
                }
              </style>\';
    } 
    else
    {
      echo \'<style type"text/css">
            #navheader{
              left:730px; 
            }
        </style>\';
    }
?>
<!DOCTYPE html>
<html>
<head>
  <title>1660662 - Hoàng Trọng Trung</title>
  <meta charset="utf-8">
  <link rel="stylesheet"  type="text/css" href="styles.css">
  <link rel="stylesheet" type="text/css" href="StyleSheet1.css">
    <link href="https://fonts.googleapis.com/css?family=Pacifico&amp;subset=vietnamese" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script type="text/javascript">
    function submitForm(frmName) {
   // Get the first form with the name
      var frm = document.getElementsByName(frmName)[0];
        frm.submit(); // Submit
        frm.reset();  // Reset
      return false; // Prevent page refresh
  }
  </script>
  
</head>
<body id="sb_site">
  <div class="container">
    <div style="position: fixed;width: 1100px;z-index: 15;" class="alert alert-primary" role="alert">
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="position: relative;">
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <form class="form-inline my-2 my-lg-0">
          <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
        <ul id="navheader" class="navbar-nav mr-auto" style="margin-right: 0px;position: absolute;" >
          <?php if(!$personalID): ?>
          <li class="nav-item <?php echo $page === \'login\'? \'active\':\'\'?>">
            <a class="nav-link" href="login.php">Đăng nhập</a>
          </li>
          <li class="nav-item  <?php echo $page === \'regist\'? \'active\':\'\'?>">
            <a class="nav-link" href="regist.php">Đăng kí</a>
          </li>
          <?php else:?>
          <li class="nav-item ">
            <a class="nav-link" href="http://localhost:8080/MangXaHoi/change_pass.php">Đổi mật khẩu</a>
          </li>

          <li class="nav-item ">
            <a class="nav-link" href="http://localhost:8080/MangXaHoi/logout.php">Đăng xuất</a>
          </li>
          <?php endif; ?>
          <li class="nav-item header_button <?php echo $page === \'index\'? \'active\':\'\'?>">
            <a class="nav-link" href="http://localhost:8080/MangXaHoi/">
              <?php
                if($page === \'index\')
                {
                  echo \'Trang chủ\';
                }
                else
                {
                  if(!$personalID)
                    echo \'Trang chủ\';
                  else
                    echo \'Trang cá nhân\';
                }
              ?>
            </a>
          </li>
        </ul>
      </div>
    </nav>
    </div>';
     $contentFooter='
      </div>
    </body>
    </html>
     ';
/*----------------------------------------------------------------------------------------------------*/
     $contentStyle1 ='
     
      *{
    border-radius:3px;
}
body{
    height:100%;

}
#selectProfile
{
    width: 17.5%;
    height: 22%;
    left: 28px;
    top: 294px;
    border-bottom-left-radius: 75px;
    border-bottom-right-radius: 75px;
    background-color: rgba(255, 255, 255,0.5);
    display: none;
    position: absolute;
}
#profile_cover:hover + #selectProfile
{
    display: inline-block;
}
#selectProfile:hover
{
    display: inline-block;   
}
#selectProfile img {
    position: relative;
    left: 60px;
    bottom: -10px;
}
#selectProfile span 
{
    position: relative;
    left: 6px;
    bottom: -40px;
}
#profile_cover {
    width: 18%;
    height: 44%;
    left: 3%;
    top: 220px; 
    background-size: cover;
    background-repeat: no-repeat;
    border-radius: 77px;
    border: 3px solid rgb(115, 115, 115);
    position:absolute;
}
#header {
    width: 845px;
    height: 352px;
    background-repeat: no-repeat;
    position: relative;
    background-position: center;
    background-size: contain;
    margin-left: 0px;
}
.position_sector{
    position:absolute;
}
.background_sector {
    background-color: white;
}

.option_header {
    z-index: -1;
    text-align: center;
    line-height: 42px;
    font-weight: bold;
    color: hsla(240, 100%, 27%,0.5);
    font-size:13px;
}
#user_name {
    left: 205px;
    bottom: 50px;
    color: rgb(115, 115, 115);
    font-family: \'Pacifico\', cursive;
    font-size: 30px;
}
#nick_name {
    left: 368px;
    bottom: 8px;
    color: white;
    font-family: \'Pacifico\', cursive;
    font-size: 25px;
    color: rgb(115, 115, 115);
}
#name {
    margin-top: 0px;
    margin-bottom: 0px;
}
#gioi_thieu {
    position: relative;
    width: 310px;
    left: 0px;
    bottom: 0px;
    height: 410px;
    text-align: center;
}
#sb_site {
    background-color: rgba(13, 49, 114,0.10);
}

#middle {
        position: relative;
    width: 875px;
    height: 100%;
    left: 6%;
    bottom: -40px;
}
#lien_lac {
    position: fixed;
    width: 252px;
    height: 545px;
    left: 970px;
    bottom: -44px;
    overflow-y: scroll;
}
#trong {
    width: 200px;
    height: 44px;
    left: 0px;
    bottom: -44px;
}
#dong_thoi_gian {
    width: 160px;
    height: 44px;
    left: 202px;
    bottom: -44px;
}
#gioi_thieu_layout {
    width: 109px;
    height: 44px;
    left: 364px;
    bottom: -44px;
}
#ban_be {
    width: 106px;
    height: 44px;
    left: 475px;
    bottom: -44px;
}
#luu_tru {
    width: 91px;
    height: 44px;
    left: 649px;
    bottom: -44px;
}
#xem_them {
    width: 101px;
    height: 44px;
    left: 742px;
    bottom: -44px;
}
#anh {
    width: 64px;
    height: 44px;
    left: 583px;
    bottom: -44px;
}
#gt_header {
    width: 109px;
    position: absolute;
    margin-left: 4px;
    margin-top: 15px;
    font-size: 16px;
    left: -3px;
    top: -8px;
}
.icon_header{
    color:darkslateblue;
    font-size:20px;
}
.gt_format {
    text-align: left;
    display: inline-flex;
    width: inherit;
}
#gt_content {
    width: inherit;
    height: 280px;
    position: absolute;
    top: 86px;
    display: block;
    font-size: 13px;
    left: -6px;
}
.can_le_icon{
    margin:0px 15px;
}
.can_le_text{
    margin:15px 0px;
}
.lien_he{
    margin:15px 15px 15px 15px;
    font-size:14px;
    text-align:left;
}
#lh_header {
    font-weight: bold;
    margin: 15px 15px 15px 15px;
    font-size: 10px;
}
#ds_mon_hoc {
        position: absolute;
    width: 530px;
    left: 315px;
    height: 250px;
    bottom: -62px;
    font-size: 16px;
    background-color :rgba(204, 204, 204,0.4); 
}
.dsmh_header {
    margin-top: 15px;
    margin-left: 4px;
    display:inline-block
}
.ten_truong{
    font-size:12px;
}
.dsmh_tieude{
    font-weight:bold;
}
.dsmh_mon_hoc {
    width: 527px;
    height: 620px;
    position: relative;
    background-color: white;
    right: 30px;
    bottom: -10px;
}
.dsmh_detail {
    width: inherit;
    height: auto;
    line-height: 20px;
    position: absolute;
    padding-left: 15px;
    padding-right: 15px;

    -moz-hyphens:auto;
-ms-hyphens:auto;
-webkit-hyphens:auto;
hyphens:auto;
word-wrap:break-word;

}
.dsmh_time {
    padding-left: 3px;
    border-left: 1px dotted rgba(13, 49, 114,0.50);
}
#dsmh {
    position: relative;
    left: 8px;
    top: -7px;
}
.btn{
    width:200px;
    text-align:left;
}
#menu
{
    position: fixed;
    z-index: 1;
}

     

     ';
/*----------------------------------------------------------------------------------------------*/
  $contentStyle2 =
  '
    .user
{
    width: 450px;
    position: relative;
    left: 300px;
    top:90px;
}
.user_title
{
    text-align: center;
}
.err
{
    color : red;
}
#btnUploadImage{
    width: 50px;
}
#sharePost
{
    width: 100%;
    position: relative;
    display: inline-block;
}
#submitShare
{
    width: 75px;
    position: relative;
    left: 415px;
    bottom: -9px;
}
#selectImage
{
    background-color: rgb(242, 242, 242);
    position: relative;
    width: 75px;
}
#selectImage:hover
{
    background-color: rgb(217, 217, 217);
}
#imageShow
{
    position:  relative;
    left: 15PX;
    bottom: -63PX;
    
}
#textbox{
    width: inherit;
}
#uploadsCover
{
    height: 40px;
    width: 160px;
    position: relative;
    border: 3px solid rgb(0, 0, 0)
}
#uploadsCover span {
    font-size: 14px;
    position: relative;
    bottom: -4px;
}
#uploadsCover img{
    width: 27px;
    opacity: 0.5;
    padding-left: 7px;
    position: relative;
    bottom: -3px;
}
#nameUser{
    color: hsla(240, 100%, 27%,0.5);
    position: relative;
    left: 15px;
    bottom: -15px;

}
#timeUpload
{
    position: relative;
    left: 15px;
    bottom: -15px;
}
#guiketban
{
    position: absolute;
    left: 625px;
    bottom: 0px;
}
  
  ';
/*----------------------------------------------------------------------------------------------*/
      $fileStyle1= "Users/".$fileName."/StyleSheet1.css";
      $fileStyle2= "Users/".$fileName."/styles.css";
      $fileFooter= "Users/".$fileName."/footer.php";
      $fileHeader= "Users/".$fileName."/header.php";
      $fileIndex = "Users/".$fileName."/index.php";
      $fileFunction = "Users/".$fileName."/functions.php";
      $fileInit = "Users/".$fileName."/init.php";
      
      createNewFile($fileStyle1,$contentStyle1);
      createNewFile($fileStyle2,$contentStyle2);
      createNewFile($fileFooter,$contentFooter);
      createNewFile($fileHeader,$contentHeader);
      createNewFile($fileIndex,$contentIndex);
      createNewFile($fileInit,$contentInit);
      createNewFile($fileFunction,$contentFunction);
    }
?>