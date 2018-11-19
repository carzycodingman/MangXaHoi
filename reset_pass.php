<?php 
require_once 'init.php';
require_once 'functions.php';
  // Xử lý logic ở đây
$page = 'change_pass';
$success = false;
$errRepeatePass = "";
if(isset($_POST['password'])&&isset($_POST['repeatpassword'])
	&&isset($_POST['secret']))
{
	$pass =$_POST['password'];
	$repeatPass = $_POST['repeatpassword'];
	$secret = $_POST['secret'];
	$reset = findSecretPassword($secret);
	if($pass !== $repeatPass)
	{
		$errRepeatePass = "*Không trùng khớp";
	} 
	else
	{
		if($secret && !$reset['used'])
		{
			$hashPass = password_hash($pass,PASSWORD_DEFAULT);
			markResetPassUsed($secret);
			$strSql = "UPDATE users
				   	   SET users.password = '".$hashPass."'
				   	   WHERE users.id = '".$reset['userid']."'";
			$success = resetPassword($strSql);
			header('Location: login.php');
		}
	}
}
?>
<?php include 'header.php'; ?>
<div class="user">
<h1 class="user_title">Khôi phuc mật khẩu</h1>
<?php if(!$success):?>
	<form action="reset_pass.php" method="POST">
		<input type="hidden" name="secret" value="<?php echo $_GET['secret'];?>">
		<div class="form-group">
			<label for="password">Mật khẩu mới</label>
			<input type="password" id="password" name ="password"class="form-control" >
			<!-- <span style="color:red"><?php /*echo*/ $passErr;?></span> -->
		</div>
		<div class="form-group">
			<label for="repeatpassword">Nhập lại mật khẩu</label>
			<input type="password" id="repeatpassword" name ="repeatpassword"class="form-control" >
			<!-- <span style="color:red"><?php /*echo*/ $passErr;?></span> -->
		</div>
		<button type="submit" class="btn btn-primary">Khôi phục</button><br/>
		<!-- <span style="color:red"><?php /*echo*/ $err;?></span> -->
	</form>
<?php endif;?>
</div>
<?php include 'footer.php'; ?>