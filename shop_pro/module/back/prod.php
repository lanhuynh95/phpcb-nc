<?php
	if(empty($_GET['cid'])) $cid=111; else $cid=$_GET['cid'];
  if(empty($_GET['page'])) $page = 1; else if($_GET['page']<1) $page = 1; else $page = $_GET['page'];

// Lấy 20 sp
  $pos = ($page - 1) * 10;
	$sql = "SELECT * 
          FROM `nn_product` 
          WHERE `category_id` = $cid 
          LIMIT $pos,10" ;
	$rs = mysqli_query($link, $sql);
	
	$sql = 'select `id`,`name` from `nn_category`';
	$rs_cate = mysqli_query($link, $sql);

  //Tính tổng số trang  
  $sql = "SELECT count(*) FROM `nn_product` WHERE `active` = 1 AND `category_id` = $cid";
  $rs2 = mysqli_query($link, $sql);
  $r2 = mysqli_fetch_row($rs2);
  $noi = $r2[0];//number of items 
  $nop = ceil($noi/10);
  // Chỉnh trang 
  $frist=$page-3;
  $final=$page+3;
  $spe=$page+10;
  if($frist<1) $frist=1;
  if($final>=$nop) $final=$nop;
  if($spe>=$nop) $spe=$nop;
?>
<div id="department">
  <div class="title"><h1><i class="fa fa-book fa-2x"></i><br>DANH SÁCH SẢN PHẨM</h1></div>
  <div class="title">
    <select onChange="window.location='?mod=prod&cid='+this.value">
      <?php while($r_cate=mysqli_fetch_assoc($rs_cate)) { ?>
      <option <?php if($r_cate['id'] == $cid) echo 'selected' ?> value="<?=$r_cate['id']?>"><?=$r_cate['name']?></option>
        <?php } ?>
    </select>
  </div>
<div style="text-align: right"><i>(dvt:VND)</i></div>
<table width="700" border="1" align="center" class="category">
   <tr class="table-title">
    <th width="35" scope="col">No</th>
    <th width="151" scope="col">Tên</th>
    <th width="46" scope="col">Hình</th>
    <th width="71" scope="col">Giá</th>
    <th width="61" scope="col">Qty</th>
    <th width="120" scope="col"><a href="?mod=prod_add&cid=<?=$cid?>">+ Thêm</a></th>
  </tr>
  <?php
    $i = $pos + 1;
    while($r = mysqli_fetch_assoc($rs)) {

  ?>
  <tr class="table-list">
    <td align="center"><?=$i++?></td>
    <td><?=$r['name']?></td>
    <td align="center"><img height="100px" src="images/sanpham/<?=$r['img_url']?>"></td>
    <td align="center"><?=number_format($r['price'])?></td>
    <td align="center"><?=$r['qty']?></td>
    <td align="center"><a href="?mod=prod_upd&id=<?=$r['id']?>">Sửa</a> | <a onClick="return confirm('Bạn chắc chắn muốn xóa')" href="?mod=prod_del&id=<?=$r['id']?>">Xóa</a> | <a href="index.php?mod=detail&id=<?=$r['id']?>">Chi tiết</a></td>
  </tr>
  <?php
  }
  ?>
</table>
  <div class="page"> 
    <div class="page-text">Trang</div>
    <div class="page-text"><a href="?mod=prod&cid=<?=$cid?>&page=1">Đầu</a></div>
      <?php 
        for($i = $frist; $i<=$final; $i++) 
      { ?>
          <div class="page-number <?php if($page==$i) echo 'page-number-s'?>"><a href="?mod=prod&cid=<?=$cid?>&page=<?=$i?>"><?=$i?></a></div>
      <?php } 
      ?>
    <div class="page-number"><a href="?mod=prod&cid=<?=$cid?>&page=<?=$spe?>"><?=$spe?></a></div>
    <div class='page-text'><a href='?mod=prod&cid=<?=$cid?>&page=<?=$nop?>'>Cuối</a></div>

  </div>
</div>
