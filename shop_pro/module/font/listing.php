<?php
	//Lay danh sach chung loai
	$sql = 'SELECT `id`,`name` FROM `nn_department` WHERE `active` = 1 ORDER BY `order`';
	$rs_dept = mysqli_query($link, $sql);
	
	if(empty($_GET['cid']))
		$cid = 111;
	else
		$cid = $_GET['cid'];
	
	if(empty($_GET['page']))
		$page = 1;
	else
		$page = $_GET['page'];
	
	if(empty($_GET['sort']))
		$sort = 1;
	else
		$sort = $_GET['sort'];
	
	$pos = ($page - 1) * 16;
	
	if($sort == 1)
		$order = '`price` ASC';
	if($sort == 2)
		$order = '`price` DESC';
	if($sort == 3)
		$order = '`name` ASC';
	if($sort == 4)
		$order = '`name` DESC';
	
	
	//Lấy sản phẩm thuộc loại XY
	$sql = "SELECT `id`, `name`, `price`, `img_url` 
			FROM `nn_product` 
			WHERE `active` = 1 AND `category_id` = $cid
			ORDER BY $order
			LIMIT $pos,16";
			
	$rs_product = mysqli_query($link, $sql);
	
	//Tính tổng số trang
	$sql = "SELECT count(*) 
			FROM `nn_product` 
			WHERE `active` = 1 AND `category_id` = $cid";
	$rs = mysqli_query($link, $sql);
	$r = mysqli_fetch_row($rs);
	$noi = $r[0];//number of items
	
	$nop = ceil($noi/16);
	
?>

        	<h4 class="heading colr">SẢN PHẨM MỚI NHẤT</h4>
            <div class="small_banner">
            	<a href="#"><img src="images/small_banner.gif" alt="" ></a>
            </div>
            <div class="sorting">
            	<p class="left colr"><?=$noi?> Item(s)</p>
                <ul class="right">                	
                    <li class="text">Trang
                    <?php
						for($i = 1; $i <= $nop; $i++)
						{
					?>
                        <a <?php if($i == $page) echo 'class="current"' ?> href="?mod=listing&cid=<?=$cid?>&sort=<?=$sort?>&page=<?=$i?>" class="colr"><?=$i?></a> 
                    <?php
						}
					?>
                    </li>
                </ul>
                <div class="clear"></div>
                <ul class="right">
                	<li class="text">
                    	Tìm Kím Theo : 
                    	<a <?php if($sort >= 3) echo 'class="current"' ?> href="?mod=listing&cid=<?=$cid?>&sort=<?php if($sort==3) echo 4;else echo 3 ?>" class="colr">TÊN <?php if($sort==3) echo '<img src="images/asc.png" alt="asc">';if ($sort==4) echo '<img src="images/desc.png" alt="desc">'?></a> | 
                        <a <?php if($sort < 3) echo 'class="current"' ?> href="?mod=listing&cid=<?=$cid?>&sort=<?php if($sort==1) echo 2;else echo 1 ?>" class="colr">GIÁ <?php if($sort==1) echo '<img src="images/asc.png" alt="asc">';if ($sort==2) echo '<img src="images/desc.png" alt="desc">'?></a> 
                    </li>
                </ul>
          	</div>
            <div class="listing">
            	<h4 class="heading colr">SẢN PHẨM MỚI NHẤT TRONG NĂM 2018</h4>
                <ul>
                <?php
					$i = 1;
					while($r = mysqli_fetch_assoc($rs_product))
					{
				?>
                	<li <?php if($i % 4 == 0) echo 'class="last"'; $i++; ?>>
                    	<a href="?mod=detail&id=<?=$r['id']?>" class="thumb"><img src="images/sanpham/<?=$r['img_url']?>" alt="" ></a>
                        <h6 class="colr"><?=$r['name']?><br><span style="color: green; font-weight: bold;"><?=number_format($r['price'])?> VNĐ</span></h6>

                        <div class="stars" style="margin-top: 50px">
                        	<a href="#"><img src="images/star_green.gif" alt="" ></a>
                            <a href="#"><img src="images/star_green.gif" alt="" ></a>
                            <a href="#"><img src="images/star_green.gif" alt="" ></a>
                            <a href="#"><img src="images/star_green.gif" alt="" ></a>
                            <a href="#"><img src="images/star_grey.gif" alt="" ></a>
                            <a href="#">(3) Reviews</a>
                        </div>
                        <div class="addwish">
                        	<a href="#">Add to Wishlist</a>
                            <a href="#">Add to Compare</a>
                        </div>
                        <div class="cart_price">
                        	<a href="?mod=cart_process&act=1&id=<?=$r['id']?>&qty='+$('#qty').val()" class="adcart">LƯU VÀO GIỎ<br><i class="fa fa-cart-plus"></i></a>
                            <p class="price"><?=number_format($r['price']/1000000,2)?> Tr</p>
                        </div>
                    </li>
                <?php
					}
				?>
                   
                </ul>
            </div>
            