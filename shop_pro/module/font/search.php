<?php
	//Lay danh sach loại
	$sql = 'SELECT `id`,`name` FROM `nn_category` WHERE `active` = 1';
	$rs_cate = mysqli_query($link, $sql);
	
	//Lay tu khoa
	if(empty($_REQUEST['search'])) $search =""; else $search = $_REQUEST['search'];
	$cond = "`active` = 1 AND `name` like '%$search%'";
	
	//Lay loại
	if(empty($_REQUEST['cid'])) $cid =0; else {
	$cid = $_REQUEST['cid'];
	if($cid > 0)
		$cond = $cond . " AND `category_id` = $cid"; }
	//Lấy giá
	
	if(empty($_REQUEST['price'])) $price =0; else {
	$price = $_REQUEST['price'];
	if($price == 1)
		$cond = $cond . " AND `price` < 100000";
	if($price == 2)
		$cond = $cond . " AND `price` between 100000 and 300000";
	if($price == 3)
		$cond = $cond . " AND `price` between 300000 and 500000";
	if($price == 4)
		$cond = $cond . " AND `price` between 500000 and 1000000";
	if($price == 5)
		$cond = $cond . " AND `price` > 1000000"; }
	
	
	if(empty($_GET['page']))
		$page = 1;
	else
		$page = $_GET['page'];
	
	if(empty($_GET['sort']))
		$sort = 1;
	else
		$sort = $_GET['sort'];
	
	$pos = ($page - 1) * 20;
	
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
			WHERE $cond
			ORDER BY $order
			LIMIT $pos,20";
			
	$rs_product = mysqli_query($link, $sql);
	
	//Tính tổng số trang
	$sql = "SELECT count(*) 
			FROM `nn_product` 
			WHERE $cond";
	$rs = mysqli_query($link, $sql);
	$r = mysqli_fetch_row($rs);
	$noi = $r[0];//number of items
	
	$nop = ceil($noi/20);
	
?>

        	<h4 class="heading colr">Featured Products</h4> 
            <div >
            			<form action="?mod=search" method="post">
              			<ul class="forms">
                        <li class="inputfield">
                        	<input type="search" name="search" class="bar" value="<?=$search?>" placeholder="Bạn tìm gì ...?" >
                            <select name="cid">
                             <option value="0">--- Chọn loại ---</option>
                            	<?php
									while($r = mysqli_fetch_assoc($rs_cate))
									{
								?>
                            			<option <?php if($r['id'] == $cid) echo 'selected' ?> value="<?=$r['id']?>"><?=$r['name']?></option>
                                <?php
									}
								?>
                            </select>
                            <select name="price">
                              <option value="0">--- Chọn giá ---</option>
                              <option <?php if(1==$price) echo 'selected'; ?> value="1">Dưới 1 trăm</option>
                              <option <?php if(2==$price) echo 'selected'; ?> value="2">Từ 1 đến 3 trăm</option>
                              <option <?php if(3==$price) echo 'selected'; ?> value="3">Từ 3 đến 5 trăm</option>
                              <option <?php if(4==$price) echo 'selected'; ?> value="4">Từ 5 đến 1 triệu</option>
                              <option <?php if(5==$price) echo 'selected'; ?> value="5">Trên 1 triệu</option>
                            </select>
                            <button type="submit">Tìm</button>
                        </li>
              </ul>
            </form>
            </div>
            <div class="sorting">
            	<p class="left colr"><?=$noi?> Item(s)</p>
                <ul class="right">                	
                    <li class="text">Page
                    <?php
						for($i = 1; $i <= $nop; $i++)
						{
					?>
                        <a <?php if($i == $page) echo 'class="current"' ?> href="?mod=search&search=<?=$search?>&sort=<?=$sort?>&cid=<?=$cid?>&price=<?=$price?>&page=<?=$i?>" class="colr"><?=$i?></a> 
                    <?php
						}
					?>
                    </li>
                </ul>
                <div class="clear"></div>
                <p class="left">View as: <a href="#" class="bold">Grid</a>&nbsp;<a href="#" class="colr">List</a></p>
                <ul class="right">
                	<li class="text">
                    	Sort by Position
                    	<a <?php if($sort >= 3) echo 'class="current"' ?> href="?mod=listing&cid=<?=$cid?>&sort=<?php if($sort==3) echo 4;else echo 3 ?>" class="colr">Name <?php if($sort==3) echo '<img src="images/asc.png" alt="asc">';if ($sort==4) echo '<img src="images/desc.png" alt="desc">'?></a> | 
                        <a <?php if($sort < 3) echo 'class="current"' ?> href="?mod=listing&cid=<?=$cid?>&sort=<?php if($sort==1) echo 2;else echo 1 ?>" class="colr">Price <?php if($sort==1) echo '<img src="images/asc.png" alt="asc">';if ($sort==2) echo '<img src="images/desc.png" alt="desc">'?></a> 
                    </li>
                </ul>
          	</div>
            <div class="listing">
            	<h4 class="heading colr">New Products for March 2010</h4>
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
                        	<a href="?mod=cart_process&act=1&id=<?=$r['id']?>&qty='+$('#qty').val()" class="adcart">Add to Cart</a>
                            <p class="price"><?=number_format($r['price']/1000000,2)?> Tr</p>
                        </div>
                    </li>
                <?php
					}
				?>
                   
                </ul>
            </div>
            