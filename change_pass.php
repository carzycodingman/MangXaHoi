<?php 
require_once 'init.php';
require_once 'functions.php';
  // Xử lý logic ở đây
$page = 'change_pass';
$success = false;
$errRepeatePass = "";
if(isset($_POST['password'])&&isset($_POST['repeatpassword']))
{
	$pass = $_POST["password"];
	$repeatPass = $_POST["repeatpassword"];
	if ($repeatPass !== $pass)
	{
		$errRepeatePass = "Không trung khớp.";
	}
	else
	{
		$hashPass = password_hash($pass,PASSWORD_DEFAULT);
		$strSql = "UPDATE users
				   SET users.password = '".$hashPass."'
				   WHERE users.id = '".$currentUser['id']."'";
		$success = resetPassword($strSql);
		header("Location:index.php");
	}
}
?>
<?php include 'header.php'; ?>
<div class="user">
<h1 class="user_title">Đổi mật khẩu</h1>
<?php if(!$success):?>
	<form action="change_pass.php" method="POST">
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
		<button type="submit" class="btn btn-primary">Đổi mật khẩu</button><br/>
		<!-- <span style="color:red"><?php /*echo*/ $err;?></span> -->
	</form>
<?php endif;?>
</div>
<?php include 'footer.php'; ?>