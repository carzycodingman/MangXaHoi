<?php 
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

	function findAllPostsById($userid)
	{
		global $db;
		$stmt = $db->prepare("SELECT * 
							  FROM posts AS p
							  WHERE p.userid = ?
							  ORDER BY p.createdat DESC");
		$stmt->execute(array($userid));
		$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $posts;
	}
	function findAllImagesById($userid)
	{
		global $db;
		$stmt = $db->prepare("SELECT * 
							  FROM post_images AS pi
							  LEFT JOIN posts AS p ON p.id = p.postid
							  WHERE pi.userid = ?
							  ORDER BY pi.uploaded_on DESC");
		$stmt->execute(array($userid));
		$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $posts;
	}
	function createUser($email,$fullname,$passwordHash)
	{
		global $db;
		$stmt = $db->prepare("INSERT INTO users(email,fullname,password)
							  VALUES (?,?,?)");
		$stmt->execute(array($email,$fullname,$passwordHash));
		return $db->lastInsertId();	
	}
	function test_input($data)
	{
		$data = trim($data);
		$data=stripcslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	function uploadImage($nameImage,$sizeImage,$tempImage,$userid,$postid)
	{	
		global $db;
		// Check if image file is a actual image or fake image
		if(!empty($nameImage)) 
		{
			$allowType =  array('jpg','png','jpeg','gif');
			$target_dir = "uploads/";
			$target_file = $target_dir . basename($nameImage);
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			if(file_exists($target_file))
				return 0;
			if(in_array($imageFileType, $allowType))
			{
	    		$check = getimagesize($tempImage);
    			if($check !== false) 
    			{
    				move_uploaded_file($tempImage, $target_file);
    				$insertImage = $db->query("INSERT INTO post_images (name_image,userid,postid,uploaded_on) VALUES('".$nameImage."','".$userid."','".$postid."',NOW())");
    				if($insertImage)
    				{
    					return 1;
    				}
    				else
    					return 0;
	    		} else
    			{
        			return -1;
    			}
    		}
    		else
    			return -2;
		}
		return -3;
	}
	function UploadPost($content,$userid)
	{
		global $db;
		$stmt = $db->prepare("INSERT INTO posts(content,userid,createdat) 
							  VALUES (?,?,NOW())");
		$stmt->execute(array($content,$userid));
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		return;
	}
	function MaxIdPost()
	{
		global $db; 
		$stmt = $db->prepare("SELECT MAX(id) as max
							  FROM posts");
		$stmt->execute();
		$maxid = $stmt->fetch(PDO::FETCH_ASSOC);
		return $maxid['max'];
	}
	?>