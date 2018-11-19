<?php 
require_once 'init.php';
require_once 'functions.php';
  // Xử lý logic ở đây
$page = 'forgot_pass';
$success = false;
$errorEmail = "";
$email = "";
if(isset($_POST['email']))
{
	$email = $_POST['email'];
	$user = findUserByEmail($email);
	if(!$user)
	{
		$errorEmail = "*Email dose not exists.";
	}
	else
	{
		$passReset =  createPasswordReset($user['id']);
		$emailSent = sentEmail($user['email'],$user['fullname'],"[FacebookFake] Yeu cau doi mat khau.","Click <a href = 'http://localhost:8080/MangXaHoi/reset_pass.php?secret=".$passReset."'>vào đây</a>");
		$success = true;
	}
}
?>
<?php include 'header.php'; ?>
<div class="user">
<h1 class="user_title">Yêu cầu đổi mật khẩu. </h1>
<?php if(!$success):?>
	<form action="forgot_pass.php" method="POST">
		<div class="form-group">
			<label for="email" >Địa chỉ email</label>
			<input type="email" id="email" name="email"class="form-control" value="<?php echo $email;?>">
		</div>
		<div style="position: relative;left: 249px;">			
			<button type="submit" class="btn btn-primary">Yêu cầu</button><br/>
			<span style="color:red;position: relative;"><?php echo $errorEmail;?></span>
		</div>
	</form>
<?php else:?>
	<p>Yêu cầu đã được gửi đến Email của bạn.</p>
<?php endif;?>
<?php include 'footer.php'; ?>