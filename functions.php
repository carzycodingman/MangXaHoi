<?php
    // Import PHPMailer classes into the global namespace
    // These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require 'vendor/autoload.php';
   function resetPassword($strSql)
   {
      global $db;    
      $newPass = $db->query($strSql);
      return $newPass;
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
          $strSql2 = "INSERT INTO post_images (content,userid,uploaded_on) VALUES('".$content."','".$userid."',NOW())";
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
         $allowType =  array('jpg','png','jpeg','gif');
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
                     delete_directory($dirname.'/'.$file);
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
   function createUser($email,$fullname,$passwordHash)
   {
      global $db;
      $stmt = $db->prepare("INSERT INTO users(email,fullname,password)
                       VALUES (?,?,?)");
      $stmt->execute(array($email,$fullname,$passwordHash));
      return $db->lastInsertId();   
   }
   function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) 
    {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }
   function createPasswordReset($userid)
   {
      global $db;
      $secret = generateRandomString();
      $stmt = $db->prepare("INSERT INTO pass_reset(userid,secret,used,createdAt)
                       VALUES (?,?,0,NOW())");
      $stmt->execute(array($userid,$secret));
      return $secret;
   }
   DEFINE ('FTP_USER','heroboy102');
   DEFINE('FPT_PASS','A123123123');
   function userMkdir($path)
   {
      $path = explode("/",$path);
        $conn_id = @ftp_connect("files.000webhost.com");
        if(!$conn_id) {
            return false;
        }
        if (@ftp_login($conn_id, FTP_USER, FTP_PASS)) {
            
            foreach ($path as $dir) {
                if(!$dir) {
                    continue;
                }
                $currPath.="/".trim($dir);
                if(!@ftp_chdir($conn_id,$currPath)) {
                    if(!@ftp_mkdir($conn_id,$currPath)) {
                        @ftp_close($conn_id);
                        return false;
                    }
                    @ftp_chmod($conn_id,0777,$currPath);
                }
            }
        }
        @ftp_close($conn_id); 
        return $currPath;
    
   }
   function sentEmail($email,$receiver,$subject,$content)
   {
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
   /* try {*/
    //Server settings
     /* $mail->SMTPDebug = 2;*/                                 // Enable verbose debug output
      $mail->isSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'tanhro966@gmail.com';                 // SMTP username
      $mail->Password = 'bbkdnltha';                           // SMTP password
      $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;                                    // TCP port to connect to

      //Recipients
      $mail->setFrom('tanhro966@gmail.com', 'Hoang Trong Trung');
      $mail->addAddress($email, $receiver);

      //Content
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = $subject;
      $mail->Body    = $content;

      $mail->send();
      return true;
      /*} 
      catch (Exception $e) 
      {
        return false;
      }*/
   }
   ?>