<?php 
require_once 'init.php';
require_once 'functions.php';
  // Xử lý logic ở đây

$page = 'requests_friend';
$index = 1;
$success = false;
$allRequest = findAllRequestFriend($currentUser['id']);
$userAccepted = false;
if(isset($_POST['accept'])&&isset($_POST['idaccepted']))
{
	$userAccepted = $_POST['idaccepted'];
	if($userAccepted)
	{
		$strSql = @"UPDATE request_friend
				   SET accepted = 1
				   WHERE sent_userid = '".$userAccepted."' AND
				   received_userid = '".$currentUser['id']."' AND
				   accepted = 0";
		$strSql1 = @"INSERT INTO friend_list(userid, friendid) 
					 VALUES('".$userAccepted."','".$currentUser['id']."')";
		executeNonQuery($strSql1);
		$success =  executeNonQuery($strSql);
		$userAccepted = false;
		header('Location:requests_friend.php');
	}
}

?>
<?php include 'header.php'; ?>
<div class="user">
<h1 class="user_title">Danh sách yêu cầu kết bạn</h1>
<?php if($success == false):?>
	<?php foreach($allRequest as $request):?>
		<form action="requests_friend.php" method="POST" name="accepted<?php echo $index;?>">
			<input type="text" name="idaccepted" style="display: none;" value="<?php echo $request['sent_userid'];?>" >
		<div style="position: relative;left: 45px;">
			<label class="alert alert-dark" role="alert"><?php echo $request['fullname']; ?></label>
			<button type="submit" name="accept" class="btn btn-primary" style="height: 50px;position: absolute;">Đồng ý</button><br/>
		</div>
	</form>
	<?php endforeach;?>
<?php endif;?>
</div>
<?php include 'footer.php'; ?>