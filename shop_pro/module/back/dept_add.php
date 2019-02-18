<?php
  $error="";
	if(isset($_POST['name']))
	{
		$name = $_POST['name'];
		$order = $_POST['order'];
		$active = $_POST['active'];
		
    if($name == '') 
      $error = 'Bạn phải nhập tên sản phẩm'; 
        else {    $sql = "INSERT INTO `nn_department` VALUES(NULL,'$name','$order','$active')";
                  mysqli_query($link, $sql);
                  header('location:?mod=dept');
            }
    } 
?>
<div id="department">
  <div class="title"><h1><i class="fa fa-newspaper-o fa-2x"></i><br>THÊM CHỦNG LOẠI</h1></div>
  <div id="error"><?=$error?></div>
  <form action="" method="post">
    <table class="table">
      <tr>
          <td style="padding-right: 100px;" >Tên <span class="req">*</span></td>
          <td><input type="text" name="name"></td>
      </tr>
      <tr>
        <td>Ẩn | Hiện <span class="req">*</span></td>
        <td>
            <input type="radio" name="active" value="1" checked> Hiện
            <input type="radio" name="active" value="0"> Ẩn
          </td>
        </tr>
        <tr>
          <td>Thứ tự <span class="req">*</span></td>
          <td><input type="number" name="order" min=1></td>
       </tr>
    <td colspan="2"><button type="submit" name="Login" class="butsm">Thêm</button></td>
  </table>
  </form>
</div>