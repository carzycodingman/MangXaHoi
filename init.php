<?php
	ini_set('display_errors', 'On');
     error_reporting(E_ALL);
		session_start();
	
	require_once 'functions.php';
	$currentUser = false;
	try
	{
		$db = new PDO('mysql:host=localhost;dbname=btcn06;charset=utf8','root','');
		if(isset($_SESSION['userid']))
		{
			$user = findUserById($_SESSION['userid']);
			if($user)
			{
				$currentUser = $user;
			}
		}	
	}
	catch(PDOException $e)
	{
		echo "<h1>Không thể kết nối với server.</h1>";
	}
?>