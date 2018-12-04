<?php 
require_once 'init.php';
require_once 'functions.php';
require_once 'createPersonalPage.php';
  // Xử lý logic ở đây
$page = 'regist';

$success = false;
$email = $fullname=$errFullname =$errEmail =$errPass ="";
$userexists = true;

if(isset($_POST['fullname'])&&isset($_POST['email'])&&isset($_POST['password']))
{
	$email = $_POST['email'];
	$fullname = $_POST['fullname'];
	if(!empty($email))
		$userexists = findUserByEmail($email);
	if(!$userexists)
	{
		$password = $_POST['password'];
		{
			if(!empty($password))
			{
				$fullname = $_POST['fullname'];
				if(!empty($fullname))
				{
					if(preg_match("/^[0-9]*$/",$fullname))
					{
						$errFullname = "Fullname only letters and space allowed ".$fullname.".";
					}
					else
					{
						$passwordHash = password_hash($password,PASSWORD_DEFAULT);
						$userID =  createUser($email,$fullname,$passwordHash);
						$userDir ="Users/".$email;
						$uploadDir ="Users/".$email."/Uploads";
						$profileDir ="Users/".$email."/Profile";
						$imageDir ="Users/".$email."/Images";
						$iconDir ="Users/".$email."/icon"; 
						mkdir($userDir,0777);
						mkdir($imageDir,0777);
						mkdir($profileDir,0777);
						mkdir($uploadDir,0777);
						mkdir($iconDir,0777);
						copyFile('icon/','Users/'.$email.'/icon/');
						createPersonalPage($email);
						$_SESSION['userid'] = $userID;
						$success = true;
						header('Location: profile.php');
						exit();
					}
				}
				else
				 	$errFullname = "Please enter your fullname";
			}
			else
			 $errPass = "Please enter your password.";
		}
	}
	else if(!empty($email))
		$errEmail = "Email already exists. Please choose other to regist!";
	else
	{
		if(empty($fullname))
			$errFullname = "Please enter your full name.";
		$errEmail = "Please enter your email.";
	} 
}	
?>

<?php include 'header.php'; ?>
<div class="user">
<h1 class="user_title">Đăng ký </h1>
<?php if(!$success):?>
	<form action="regist.php" method="post">
		<div class="form-group">
			<label for="fullname">Tên hiển thị</label>
			<input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo $fullname;?>">
			<span style="color:red"><?php echo $errFullname;?></span>
		</div>
		<div class="form-group">
			<label for="email">Địa chỉ email</label>
			<input type="email" class="form-control" id="email" name="email" value="<?php echo $email;?>">
			<span style="color:red"><?php echo $errEmail;?></span>
		</div>
		<div class="form-group">
			<label for="password">Mật khẩu</label>
			<input type="password" class="form-control" id="password" name ="password">
			<span style="color:red"><?php echo $errPass;?></span>	
		</div>
		<button type="submit" class="btn btn-primary">Đăng ký</button><br/>
	</form>
<?php endif;?>
</div>
<?php include 'footer.php'; ?>