<?php
  $de_id=$_GET['id'];

	$error="";
	if(isset($_POST['name']))
	{
		$name = $_POST['name'];
		$order = $_POST['order'];
		$active = $_POST['active'];		
		
		//Kiểm tra data
		if($name == '') 
			$error = 'Bạn phải nhập tên chủng loại';
        else {    $sql = "UPDATE `nn_department` SET `name`='$name',`order`='$order' ,`active`='$active' WHERE `id`=$de_id"; 
                    $rs=mysqli_query($link,$sql);
				     header('location:?mod=dept');
            }
		}	
	$sql='SELECT * FROM `nn_department` WHERE `id`='.$de_id;
	$rs=mysqli_query($link,$sql);
	$r=mysqli_fetch_assoc($rs);
    ?>
<div id="department">
  <div class="title"><h1><i class="fa fa-newspaper-o fa-2x"></i><br>CẬP NHẬT CHỦNG LOẠI</h1></div>
  <div id="error"><?=$error?></div>
  <form action="" method="post">
    <table class="table">
    	<tr>
        	<td style="padding-right: 100px;" >Tên <span class="req">*</span></td>
        	<td><input type="text" name="name" class="bar" value="<?=$r['name']?>"></td>
    	</tr>
    	<tr>
    		<td>Ẩn | Hiện <span class="req">*</span></td>
    		<td>
        	<input <?php if($r['active'] == 1) echo 'checked' ?> type="radio" name="active" value="1"> Hiện
            <input <?php if($r['active'] == 0) echo 'checked' ?> type="radio" name="active" value="0"> Ẩn
       		</td>
        </tr>
        <tr>
	    	<td>Thứ tự <span class="req">*</span></td>
	        <td><input type="number" name="order" class="bar" value="<?=$r['order']?>"></td>
		</tr>
		<td colspan="2"><button type="submit" name="Login" class="butsm">Cập nhật</button></td>
	</table>
  </form>
</div>

 
