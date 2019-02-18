<?php
	$id = $_GET['id'];
	
	//Cap nhat so luot xem
	$sql = 'UPDATE `nn_product` SET `view`=`view`+1 WHERE `id`='.$id;
	mysqli_query($link, $sql);
	
	//Lay thong tin hien tai cua san pham
	$sql = 'SELECT `category_id`, `name`, `price`, `desc`, `detail`, `img_url`, `qty`, `view` 
			FROM `nn_product` 
			WHERE `id` = '.$id;
	$rs = mysqli_query($link, $sql);
	
	$r =  mysqli_fetch_assoc($rs);
	
	
	//Lay 20 san pham moi nhat cung loai
	$sql = "SELECT `id`, `name`, `price`, `img_url` 
			FROM `nn_product` 
			WHERE `active` = 1 AND `category_id` = {$r['category_id']} AND `id` != {$id}
			ORDER BY `id` desc
			LIMIT 0,16";
	$rs_product = mysqli_query($link, $sql);
?>

        	<h4 class="heading colr"><?=$r['name']?></h4>
            <div class="prod_detail">
            	<div class="big_thumb">
                	<div id="slider2">
                        <div class="contentdiv">
                        	<img src="images/sanpham/<?=$r['img_url']?>" alt="" >
                            <a rel="example_group" href="images/sanpham/<?=$r['img_url']?>" title="Lorem ipsum dolor sit amet, consectetuer adipiscing elit." class="zoom">&nbsp;</a>
                      </div>
                    </div>
                    <a href="javascript:void(null)" class="prevsmall"><img src="images/prev.gif" alt="" ></a>
                    <div style="float:left; width:189px !important; overflow:hidden;">
                    <div class="anyClass" id="paginate-slider2">
                        <ul>
                            <li><a href="#" class="toc"><img src="images/sanpham/<?=$r['img_url']?>" alt="" ></a></li>
                        </ul>
                    </div>
                    </div>
                    <a href="javascript:void(null)" class="nextsmall"><img src="images/next.gif" alt="" ></a>
                    <script type="text/javascript" src="js/cont_slidr.js"></script>
                </div>
                <div class="desc">
                	<div class="quickreview">
                            <a href="#" class="bold black left"><u>Mô Tả Sản Phẩm</u></a>
                            <div class="clear"></div>
                            <p class="avail"><span class="bold">Số lượng hiện có:</span> <?=$r['qty']?></p>
                            <p class="avail"><span class="bold">Lượt xem:</span> <?=$r['view']?></p>
                          <h6 class="black">Mô Tả</h6>
                        <p>
                        	<?=$r['desc']?> 
                        </p>
                    </div>
                    <div class="addtocart">
                    	<h4 class="left price colr bold"><?=number_format($r['price'])?> VNĐ</h4>
                            <div class="clear"></div>
                            <ul class="margn addicons">
                                <li>
                                    <a href="#">*</a>
                                </li>
                                <li>
                                    <a href="#">*</a>
                                </li>
                        	</ul>
                            <div class="clear"></div>
                        <ul class="left qt">
                   	    <li class="bold qty">QTY</li>
                            <li><input id="qty" name="qty" type="number" class="bar" min="1"></li>
                           
                            <li><a href="javascript:window.location='?mod=cart_process&act=1&id=<?=$id?>&qty='+$('#qty').val()" class="simplebtn"><span>Lưu Vào Giỏ</span></a></li>

                        </ul>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="prod_desc">
                	<h4 class="heading colr">Chi Tiết Sản Phẩm</h4>
                    <p>
                    	<?=$r['detail']?>. 
                    </p>
                </div>
            </div>
            <div class="listing">
            	<h4 class="heading colr">Sản Phẩm Mới Nhất Trong Năm 2018</h4>
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
                        	<a href="?mod=cart_add&id=<?=$r['id']?>" class="adcart">LƯU VÀO GIỎ<br><i class="fa fa-cart-plus"></i></a>
                        </div>
                    </li>
                <?php
					}
				?>
                </ul>
            </div>