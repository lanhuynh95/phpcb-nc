<?php
	//Lấy 10 sản phẩm được quan tâm nhất
	$sql = 'SELECT `id`, `name`, `price`, `img_url` 
			FROM `nn_product` 
			WHERE `active` = 1
			ORDER BY `view` desc
			LIMIT 0,10';
	$rs_hot = mysqli_query($link, $sql);
	
	//Lấy 20 sản phẩm moi nhất
	$sql = 'SELECT `id`, `name`, `price`, `img_url` 
			FROM `nn_product` 
			WHERE `active` = 1
			ORDER BY `id` desc
			LIMIT 0,20';
	$rs_new = mysqli_query($link, $sql);
?>
<div class="col2_center">
        	<h4 class="heading colr">Sản Phẩm Nổi Bật Nhất</h4>
            <div id="prod_scroller">
            <a href="javascript:void(null)" class="prev">&nbsp;</a>
       	  <div class="anyClass scrol">
                <ul>
                <?php
					while($r = mysqli_fetch_assoc($rs_hot))
					{
				?>
                    <li>
                    	<a href="?mod=detail&id=<?=$r['id']?>"><img src="images/sanpham/<?=$r['img_url']?>" alt="<?=$r['name']?>" ></a>
                        <h6 class="colr"><?=$r['name']?></h6>
                        <p class="price bold"><?=number_format($r['price'])?> VNĐ</p>
                        <a href="?mod=cart_process&act=1&id=<?=$r['id']?>&qty='+$('#qty').val()" class="adcart">LƯU VÀO GIỎ <i class="fa fa-cart-plus"></i></a>
                    </li>
                <?php
					}
				?>
                </ul>
			</div>
            <a href="javascript:void(null)" class="next">&nbsp;</a>
        </div>
            <div class="clear"></div>
            <div class="listing">
            	<h4 class="heading colr">Sản Phẩm Mới Nhất Trong Năm 2018</h4>
                <ul>
                <?php
					$i = 1;
					while($r = mysqli_fetch_assoc($rs_new))
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
                        </div>
                    </li>
                <?php
					}
				?>
                </ul>
            </div>
            </div>