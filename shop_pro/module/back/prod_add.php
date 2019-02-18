<?php
$error="";
  if(empty($_GET['cid'])) $cid=113;$cid=$_GET['cid'];
	if(isset($_POST['name']))
	{
		//print_r($_POST);
		//print_r($_FILES);

		$name = $_POST['name'];
		$price = $_POST['price'];
		$active = $_POST['active'];
		$qty = $_POST['qty'];
		$note = $_POST['note'];
		$desc = $_POST['desc'];
		$detail = $_POST['detail'];
		$category_id = $_POST['category_id'];

		//Xu ly file
		/*Array
		(
			[img] => Array
				(
					[name] => Chrysanthemum.jpg
					[type] => image/jpeg
					[tmp_name] => C:\xampp\tmp\phpB947.tmp
					[error] => 0
					[size] => 879394
				)
		)*/
		$file = $_FILES['img']['name'];
    	$filem=$_FILES['img']['tmp_name'];

		if($name=="") $error="Bạn phải nhập tên sản phẩm";
		else if($price=="") $error="Bạn phải nhập giá";
    	else if($active=="") $error="Bạn phải nhập Ẩn|Hiện";
    			//Neu user co upload file
    	else if($file=="") $error="Bạn phải nhập hình ảnh"; 
    	else if($category_id=="") $error="Bạn phải nhập loại sản phẩm";
    	else if($qty=="") $error="Bạn phải nhập số lượng";
    	else {
		//Insert vao DB	
		$sql = "INSERT INTO `nn_product` VALUES(NULL,'$category_id','$name','$price','$desc','$detail','$file',now(),'$qty','$note','0','0','$active')";
		mysqli_query($link, $sql);
		copy($filem,'images/sanpham/'.$file);

		header("location:?mod=prod&cid=$category_id");
		}
	}	
	//Lay danh sach loai san pham
	$sql = 'select `id`,`name` from `nn_category`';
	$rs_cate = mysqli_query($link, $sql);
?>
<div id="department">
  <div class="title"><h1><i class="fa fa-book fa-2x"></i><br>THÊM SẢN PHẨM</h1></div>
  <div id="error"><?=$error?></div>
  <form action="" method="post" enctype="multipart/form-data">
    <table class="table" style="margin: 0px -200px">
    <tr>
        <td width="122" align="center" scope="row">Tên</td>
        <td width="262"><input type="text" name="name" value="<?php if(isset($_POST['name'])) echo $name ?>"></td>
      </tr>
      <tr>
        <td align="center" scope="row">Giá</td>
        <td><input type="number" name="price" id="price" value="<?php if(isset($_POST['price'])) echo $price?>"></td>
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
        <td align="center" scope="row">Hình</td>
        <td align="left" scope="row">
        	<input type="file" name="img">
        </td>
      </tr>
      <tr>
        <td align="center" scope="row">Loại SP</td>
        <td align="left" scope="row"><select name="category_id" id="category_id">
          <?php
					while($r = mysqli_fetch_assoc($rs_cate))
					{
				?>
                      <option <?php if( $r['id'] == $cid) echo 'selected'?> value="<?=$r['id']?>"><?=$r['name']?></option>
          <?php
					}
				?>
        </select></td>
      </tr>
      <tr>
        <td align="center" scope="row">Số lượng</td>
        <td align="left" scope="row"><input type="number" name="qty" id="qty" width="220px" value="<?php if(isset($_POST['qty'])) echo $qty?>"></td>
      </tr>
      <tr>
        <td align="center" scope="row">Ghi chú</td>
        <td align="left" scope="row">
        	<textarea class="ckeditor" name="note" cols="29" rows="5"><?php if(isset($_POST['note'])) echo $note?></textarea>
        </td>
      </tr>
      <tr>
        <td align="center" scope="row">Mô tả tóm tắt</td>
        <td align="left" scope="row"><textarea name="desc" id="desc" cols="29" rows="5"><?php if(isset($_POST['desc'])) echo $desc?></textarea></td>
      </tr>
      <tr>
        <td align="center" scope="row">Mô tả chi tiết</td>
        <td align="left" scope="row" colspan="2"><textarea name="detail" id="detail" cols="29" rows="5"><?php if(isset($_POST['detail'])) echo $detail?></textarea></td>
      </tr>
		<td colspan="2"><button type="submit" name="Login" class="butsm">THÊM</button></td>
	</table>
  </form>
</div>
<script type="text/javascript" src="lib/ckeditor/ckeditor.js"></script>