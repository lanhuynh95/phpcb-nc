
                <h2 class="heading colr">Danh Sách Hàng Trong Giỏ</h2>
                <div class="shoppingcart">
                	<form action="?mod=cart_process&act=3" method="post" id="cart">
            	<ul class="tablehead">
                	<li class="remove colr">Thứ Tự</li>
                    <li class="thumb colr">&nbsp;</li>
                    <li class="title colr">Tên Sản Phẩm</li>
                    <li class="price colr">Giá</li>
                    <li class="qty colr">SL</li>
                    <li class="total colr">Tổng</li>
                </ul>
                <?php 
					if(isset($_SESSION['cart'])) {
                        $cart= $_SESSION['cart'] ;
                    } else {
                        $_SESSION['cart']=array(); 
                    };

					$total=0;
					foreach($cart as $k => $v)
					
					{
					$sql='SELECT `name`,`price`,`img_url` FROM `nn_product` WHERE `id`='.$k;
					$rs = mysqli_query($link,$sql);
					$r = mysqli_fetch_assoc($rs);
					
					$total = $total + ($v*$r['price']) ;
					
				?>
                <ul class="cartlist gray">
                	<li class="remove txt">
                    <a onClick="return confirm('Are you sure')" href="?mod=cart_process&act=2&id=<?=$k?>">
                    <img src="images/delete.gif" alt="" >
                    </a>
                    </li>
                    <li class="thumb"><a href="?mod=detail.html"><img src="images/sanpham/<?=$r['img_url']?>" alt="" ></a></li>
                    <li class="title txt"><a href="detail.html"><?=$r['name']?></a></li>
                    <li class="price txt"><?=number_format($r['price'],0)?></li>
                    <li class="qty"><input name="<?=$k?>" type="number" value="<?=$v?>" min="1"></li>
                    <li class="total txt"><?=number_format($v*$r['price'])?></li>
                </ul>
				<?php } ?>
                <div class="clear"></div>
                <div class="subtotal">
                	<a href="?mod=listing" class="simplebtn"><span>Về Trang Sản Phẩm</span></a>
                    <a href="#" onclick="$('#cart').submit()" class="simplebtn"><span>Cập Nhật</span></a>
                      <a href="?mod=checkout" class="simplebtn"><span>Mua Hàng</span></a>
                	<h3 class="colr"><?=number_format($total)?></h3>
                </div>
                <div class="clear"></div>
                </form>
            </div>
                <div class="clear"></div>
            