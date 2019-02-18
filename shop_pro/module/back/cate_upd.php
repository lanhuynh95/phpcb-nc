<?php
$error="";
	$id = $_GET['id'];
	
	$sql = 'select * from `nn_category` where `id`= '. $id;
	$rs = mysqli_query($link, $sql);
	$r_cate = mysqli_fetch_assoc($rs);
	
	if(isset($_POST['name']))
	{
		$name = $_POST['name'];
		$order = $_POST['order'];
		$active = $_POST['active'];
		$department_id = $_POST['department_id'];
		
		if($name=="") $error="Bạn phải nhập tên sản phẩm";
   		else if($order=="") $error="Bạn phải nhập tên thứ tự";
    	else if($active=="") $error="Bạn phải nhập Ẩn/Hiện";
    	else if($department_id=="") $error="Bạn phải nhập chủng loại";
    	else {
			$sql = "UPDATE `nn_category` SET
			`department_id` = '$department_id',
			`name` = '$name',
			`order` = '$order',
			`active` = '$active'
			WHERE `id`= $id";
			mysqli_query($link, $sql);
			header('location:?mod=cate');
		}
	}
	
	//Lay danh sach chung loai
	$sql = 'select `id`,`name` from `nn_department` order by `order`';
	$rs_dept = mysqli_query($link, $sql);
?>
<div id="department">
  <div class="title"><h1><i class="fa fa-bars fa-2x"></i><br>CẬP NHẬT LOẠI SẢN PHẨM</h1></div>
  <div id="error"><?=$error?></div>
  <form action="" method="post">
    <table class="table">
    	<tr>
        <td width="122" align="center" scope="row">Tên</td>
        <td width="262"><input type="text" name="name" value="<?=$r_cate['name']?>"></td>
      </tr>
      <tr>
        <td align="center" scope="row">Thứ tự</td>
        <td><input type="number" name="order" value="<?=$r_cate['order']?>" min="1"></td>
      </tr>
      <tr>
        <td align="center" scope="row">Ẩn/Hiện</td>
        <td>
            <select name="active">
                <option <?php if($r_cate['active'] == 1) echo 'selected'?> value="1">Hiện</option>
                <option <?php if($r_cate['active'] == 0) echo 'selected'?> value="0">Ẩn</option>
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
                		<option <?php if($r_cate['department_id'] == $r['id']) echo 'selected'?> value="<?=$r['id']?>"><?=$r['name']?></option>
                <?php
					}
				?>
            </select>
        </td>
      </tr>
		<td colspan="2"><button type="submit" name="Login" class="butsm">Cập nhật</button></td>
	</table>
  </form>
</div>