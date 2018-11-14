<?php 
    require_once 'init.php';
    require_once 'functions.php';
    // Xử lý logic ở đây
    //kiểm tra có phai là file image hay không
    $page = 'upload';
    $postImage = "";
    if(isset($_FILES['fileToUpload'])&&isset($_POST['submit']))
    {
      $image = $_FILES['fileToUpload'];
      $nameImage = $image['name'];
      $sizeImage = $image['size'];
      $tempImage = $image['tmp_name'];
      $check = uploadImage($nameImage,$sizeImage,$tempImage,$currentUser['id']);
      if($check == 1)
      {
        $postImage = "thành công";
      }
    }
?>
<?php include 'header.php';?>
<div>
  <form action="upload.php" method="post" enctype="multipart/form-data">
        Select image to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit">
        <?php echo $postImage;?>
    </form>
</div>
<?php include 'footer.php';?>