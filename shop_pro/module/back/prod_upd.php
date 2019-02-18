<?php
$error="";
  $id = $_GET['id'];
  $sql = 'select * from `nn_product` where `id`= '. $id;
  $rs_prod = mysqli_query($link, $sql);
  $r_prod = mysqli_fetch_assoc($rs_prod);

	if(isset($_POST['name']))
	{
		$name = $_POST['name'];
		$price = $_POST['price'];
		$active = $_POST['active'];
		$qty = $_POST['qty'];
		$note = $_POST['note'];
		$desc = $_POST['desc'];
		$detail = $_POST['detail'];
		$category_id = $_POST['category_id'];

		  $file = $_FILES['img']['name'];
    	$filem = $_FILES['img']['tmp_name'];

		if($name=="") $error="Bạn phải nhập tên sản phẩm";
		else if($price=="") $error="Bạn phải nhập giá";
    else if($active=="") $error="Bạn phải nhập Ẩn|Hiện";
    else if($category_id=="") $error="Bạn phải nhập loại sản phẩm";
    else if($qty=="") $error="Bạn phải nhập số lượng";
    else {
         if($file=="") $img_url=$r_prod['img_url']; else {
              $img_url=$file;
              unlink('images/sanpham/'.$r_prod['img_url']); }
      		echo $sql = "UPDATE `nn_product` SET `category_id`='$category_id',`name`='$name',`price`='$price',`desc`='$desc',`detail`='$detail',`img_url`='$img_url',`qty`='$qty',`note`='$note',`active`='$active' WHERE `id`=$id";
      		mysqli_query($link, $sql);
      		copy($filem,'images/sanpham/'.$img_url);
      		header("location:?mod=prod&cid=$category_id");
      		}
	}
	//Lay danh sach loai san pham
	$sql = 'select `id`,`name` from `nn_category`';
	$rs_cate = mysqli_query($link, $sql);
?>
<div id="department">
  <div class="title"><h1><i class="fa fa-book fa-2x"></i><br>CẬP NHẬT SẢN PHẨM</h1></div>
  <div id="error"><?=$error?></div>
  <form action="" method="post" enctype="multipart/form-data">
    <table class="table" style="margin: 0px -200px">
    <tr>
        <td width="122" align="left" >Tên</td>
        <td width="262"><input type="text" name="name" value="<?=$r_prod['name']?>"></td>
      </tr>
      <tr>
        <td align="left" >Giá</td>
        <td><input type="number" name="price" id="price" value="<?=$r_prod['price']?>"></td>
      </tr>
      <tr>
        <td align="left" >Ẩn/Hiện</td>
        <td>
            <select name="active">
                <option <?php if($r_prod['active'] == 1) echo 'selected'?> value="1">Hiện</option>
                <option <?php if($r_prod['active'] == 0) echo 'selected'?> value="0">Ẩn</option>
            </select>
        </td>
      </tr>
      <tr>
        <td align="left" >Hình cũ</td>
        <td align="left" ><img src="images/sanpham/<?=$r_prod['img_url']?>" height="100"></td>
      </tr>
      <tr>
        <td align="left" >Hình thay thế</td>
        <td align="left" >
        	<input type="file" name="img">
        </td>
      </tr>
      <tr>
        <td align="left" >Loại SP</td>
        <td align="left" ><select name="category_id" id="category_id">
          <?php
					while($r = mysqli_fetch_assoc($rs_cate)) { ?>
          <option value="<?=$r['id']?>" <?php if($r_prod['category_id'] == $r['id']) echo 'selected'?>><?=$r['name']?></option>
          <?php } ?>
        </select></td>
      </tr>
      <tr>
        <td align="left" >Số lượng</td>
        <td align="left" ><input type="number" name="qty" id="qty" width="220px" value="<?=$r_prod['qty']?>"></td>
      </tr>
      <tr>
        <td align="left" >Ghi chú</td>
        <td align="left" >
        	<textarea class="ckeditor" name="note" cols="29" rows="5"><?=$r_prod['note']?></textarea>
        </td>
      </tr>
      <tr>
        <td align="left" >Mô tả tóm tắt</td>
        <td align="left" ><textarea name="desc" id="desc" cols="29" rows="5"><?=$r_prod['desc']?></textarea></td>
      </tr>
      <tr>
        <td align="left" >Mô tả chi tiết</td>
        <td align="left" ><textarea name="detail" id="detail" cols="29" rows="5"><?=$r_prod['detail']?></textarea></td>
      </tr>
		<td colspan="2"><button type="submit" name="Login" class="butsm">Cập nhật</button></td>
	</table>
  </form>
</div>
<script type="text/javascript" src="lib/ckeditor/ckeditor.js"></script>