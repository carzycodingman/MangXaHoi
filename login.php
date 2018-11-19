<?php 
require_once 'init.php';
require_once 'functions.php';
  // Xử lý logic ở đây
$page = 'login';
$success = false;
$check = false;
$email=$passErr = $emailErr =$pass1=$err =$user="";
if($_SERVER["REQUEST_METHOD"]=="POST")
{
	isset($_POST['email']);
	$email = $_POST['email'];
	$user = findUserByEmail($email); 
	$checkmail  = empty($_POST["email"]);
	$checkpass = empty($_POST["password"]); 
	if($checkmail&&!$checkpass)
	{
		$emailErr = "*Please enter your email";
	}
	else
	{
		$email1 = test_input($_POST["email"]);
	}
	if($checkpass&&$user)
	{
		$passErr = "*Please enter password";
	}
	else if(!$checkmail)
	{
		$pass1 = test_input($_POST["password"]);
	}

	if($checkmail&&$checkpass)
		$err = "*Plase enter your account!";

}

if(isset($_POST['email'])&&isset($_POST['password']))
{
	$password = $_POST['password'];
	if($user)
	{
		$check = password_verify($password,$user['password']);
		if($check==True)
		{
			$_SESSION['userid'] = $user['id'];
			header('Location: home.php');
			$success = True;
		}
		else if(empty($password))
			$passErr = "*Please enter your password!";
		else
			$passErr = "Password error. Please try again!";
	}
	else if(!empty($email)) 
		$emailErr = "*Can't find email. Please regist a user or try again!";
}
?>
<?php include 'header.php'; ?>
<div class="user">
<h1 class="user_title">Đăng nhập </h1>
<?php if(!$success):?>
	<form action="login.php" method="POST">
		<div class="form-group">
			<label for="email" >Địa chỉ email</label>
			<input type="email" id="email" name="email"class="form-control"value="<?php echo $email;?>">
			<span style="color:red"><?php echo $emailErr;?></span>
		</div>
		<div class="form-group">
			<label for="password">Mật khẩu</label>
			<input type="password" id="password" name ="password"class="form-control" >
			<span style="color:red"><?php echo $passErr;?></span>
		</div>
		<div style="position: relative;left: 45px;">
			<a href="forgot_pass.php"><button type="button" class="btn btn-primary" >Quên mật khẩu?
			</button>
			</a>			
			<button type="submit" class="btn btn-primary">Đăng nhập</button><br/>
			<span style="color:red;position: relative;left: 220px;"><?php echo $err;?></span>
		</div>
	</form>
<?php endif;?>
<?php include 'footer.php'; ?>