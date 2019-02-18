<?php
  $error="";
	if(isset($_POST['name']))
	{
		$name = $_POST['name'];
		$order = $_POST['order'];
		$active = $_POST['active'];
		$department_id = $_POST['department_id'];
		//Insert vao DB

		if($name=="") $error="Bạn phải nhập tên loại sản phẩm";
    else if($order=="") $error="Bạn phải nhập tên thứ tự";
    else if($active=="") $error="Bạn phải nhập Ẩn/Hiện";
    else if($department_id=="") $error="Bạn phải nhập chủng loại";
    else {
  		$sql = "INSERT INTO `nn_category` VALUES(NULL,'$department_id','$name','$order','$active')";
  		mysqli_query($link, $sql);
  		header('location:?mod=cate');
    }
	}
	
	//Lay danh sach chung loai
	$sql = 'select `id`,`name` from `nn_department` order by `order`';
	$rs_dept = mysqli_query($link, $sql);
?>
<div id="department">
  <div class="title"><h1><i class="fa fa-bars fa-2x"></i><br>THÊM LOẠI SẢN PHẨM</h1></div>
  <div id="error"><?=$error?></div>
  <form action="" method="post">
    <table class="table">
      <tr>
        <td width="122" align="center" scope="row">Tên</td>
        <td width="262"><input type="text" name="name" value="<?php if(isset($_POST['name'])) echo $name ?>"></td>
      </tr>
      <tr>
        <td align="center" scope="row">Thứ tự</td>
        <td><input type="number" name="order" min=1></td>
      </tr>
      <tr>
        <td align="center" scope="row">Ẩn/Hiện</td>
        <td>
            <select name="active">
                <option value="1">Hiện</option>
                <option value="0">Ẩn</option>
            </select>
        </td>
      </tr>
      <tr>
        <td align="center" scope="row">Chủng loại</td>
        <td align="left" scope="row">
           <select name="department_id">
              <?php
          while($r = mysqli_fetch_assoc($rs_dept))
          {
        ?>
                    <option value="<?=$r['id']?>"><?=$r['name']?></option>
                <?php
          }
        ?>
            </select>
        </td>
      </tr>
    <td colspan="2"><button type="submit" name="Login" class="butsm">Thêm</button></td>
  </table>
  </form>
</div>