<?php
	$sql = 'SELECT c.*, d.`name` as `dept_name` 
			FROM `nn_category` c LEFT JOIN `nn_department` d 
			ON c.`department_id` = d.`id`
			ORDER BY d.`order`,d.`id`';
	$rs = mysqli_query($link, $sql);
?>
<div id="department">
  <div class="title"><h1><i class="fa fa-bars fa-2x"></i><br>DANH SÁCH LOẠI SẢN PHẨM</h1></div>
<table width="700" border="1" align="center" class="category">
 <tr class="table-title">
    <th width="42" scope="col">No</th>
    <th width="312" scope="col">Tên</th>
    <th width="300" scope="col">Chủng loại</th>
    <th width="67" scope="col">Thứ tự</th>
    <th width="76" scope="col">Ẩn/Hiện</th>
    <th width="100" scope="col"><a href="?mod=cate_add">+ Thêm</a></th>
  </tr>
  <?php
    $i = 1;
    while($r = mysqli_fetch_assoc($rs)) {
  ?>
  <tr class="table-list">
    <td align="center"><?=$i++?></td>
    <td><?=$r['name']?></td>
    <td align="right"><?=$r['dept_name']?></td>
    <td align="center"><?=$r['order']?></td>
    <td align="center"><?=$r['active'] == 1?'Hiện':'Ẩn';?></td>
    <td align="center"><a href="?mod=cate_upd&id=<?=$r['id']?>">Sửa</a> | <a onClick="return confirm('Bạn chắc chắn muốn xóa')" href="?mod=cate_del&id=<?=$r['id']?>">Xóa</a></td>
  </tr>
  <?php
    }
  ?>
</table>
</div>