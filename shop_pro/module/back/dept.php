<?php
	$sql = 'select * from `nn_department`';
	$rs = mysqli_query($link, $sql);
	
?>
<div id="department">
  <div class="title"><h1><i class="fa fa-newspaper-o fa-2x"></i><br>DANH SÁCH CHỦNG LOẠI</h1></div>
<table width="500" border="1" align="center">
  <tr class="table-title">
    <th width="42" scope="col">No</th>
    <th width="202" scope="col">Tên</th>
    <th width="67" scope="col">Thứ tự</th>
    <th width="76" scope="col">Ẩn/Hiện</th>
    <th width="79" scope="col"><a href="?mod=dept_add">+ Thêm</a></th>
  </tr>
  <?php
  	$i = 1;
  	while($r = mysqli_fetch_assoc($rs)) {
  ?>
  <tr class="table-list">
    <td align="center"><?=$i++?></td>
    <td><?=$r['name']?></td>
    <td align="center"><?=$r['order']?></td>
    <td align="center"><?php if($r['active']==1) {echo "Hiện";} else{echo "Ẩn";}?></td>
    <td align="center"><a href="?mod=dept_upd&id=<?=$r['id']?>">Sửa</a> | <a onClick="return confirm('Bạn chắc chắn muốn xóa')" href="?mod=dept_del&id=<?=$r['id']?>">Xóa</a></td>
  </tr>
  <?php
	}
  ?>
</table>
</div>