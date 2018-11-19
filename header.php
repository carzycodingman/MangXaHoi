<?php
	if($page === 'index')
    {
    	if(!$currentUser)
      		echo '<style type"text/css">
            	#navheader{
              	left:800px; 
              	}
          		</style>';
         else
         	echo '<style type"text/css">
            	#navheader{
              	left:755px; 
              	}
          		</style>';
    } 
    else
    {
      echo '<style type"text/css">
            #navheader{
              left:730px; 
            }
        </style>';
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
					<?php if(!$currentUser): ?>
					<li class="nav-item <?php echo $page === 'login'? 'active':''?>">
						<a class="nav-link" href="login.php">Đăng nhập</a>
					</li>
					<li class="nav-item  <?php echo $page === 'regist'? 'active':''?>">
						<a class="nav-link" href="regist.php">Đăng kí</a>
					</li>
					<?php else:?>
					<li class="nav-item ">
						<a class="nav-link" href="change_pass.php">Đổi mật khẩu</a>
					</li>

					<li class="nav-item ">
						<a class="nav-link" href="logout.php">Đăng xuất</a>
					</li>
					<?php endif; ?>
					<li class="nav-item header_button <?php echo $page === 'index'? 'active':''?>">
						<a class="nav-link" href="<?php echo $page == 'index' && $currentUser ? 'home.php':'index.php'?>">
							<?php
								if($page === 'index')
								{
									echo 'Trang chủ';
								}
								else
								{
									if(!$currentUser)
										echo 'Trang chủ';
									else
										echo 'Trang cá nhân';
								}
							?>
						</a>
					</li>
				</ul>
			</div>
		</nav>
		</div>
