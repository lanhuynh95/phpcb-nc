<?php 
if(!isset($_SESSION['id'])) header('location:?mod=login');
$id_user = $_SESSION['id'];

$msg="";

$sql='SELECT * FROM `nn_user` WHERE `id`='.$id_user;
$rs=mysqli_query($link,$sql);
$r_user=mysqli_fetch_assoc($rs);

if(isset($_POST['name']))
{
	
	$name=$_POST['name'];
	$mobile=$_POST['mobile'];
	$email=$_POST['email'];
	$address=$_POST['address'];
	$remark=$_POST['remark'];
	
	
$sql="INSERT INTO `nn_order` VALUES (NULL,'$id_user',now(),'$name','$address','','$email','$mobile','$remark','0')";

 mysqli_query($link,$sql);

//lay id 
echo $order_id=mysqli_insert_id($link);

$cart=$_SESSION['cart'];
foreach($cart as $product_id => $qty) {
	$sql="SELECT `price` FROM `nn_product` WHERE id=".$product_id;
	$rs=mysqli_query($link,$sql);
	$r=mysqli_fetch_assoc($rs);
	$price=$r['price'];
	
	$sql="INSERT INTO `nn_order_detail` VALUES($order_id,$product_id,$qty,$price)";
	mysqli_query($link, $sql);
	}
?>
	<script>
    	alert('Bạn đã đặt hàng thành công');
		window.location='?mod=account';
    </script>
<?php
}
?>
                <h2 class="heading colr">ORDER INFOR</h2>
                <div class="shoppingcart">
                	
            	<ul class="tablehead">
                	<li class="remove colr">No.</li>
                    <li class="thumb colr">&nbsp;</li>
                    <li class="title colr">Product Name</li>
                    <li class="price colr">Unit Price</li>
                    <li class="qty colr">QTY</li>
                    <li class="total colr">Sub Total</li>
                </ul>
                <?php 
							
					//$cart=array(2=>10,10=>50,5=>150,7=>350);
				
					
					if(isset($_SESSION['cart']))
						$cart= $_SESSION['cart'];
					else
						$cart=array();
					
					//print_r($cart);
					
					$total=0;
					$i=1;
					foreach($cart as $k => $v)
					
					{
					$sql='SELECT `name`,`price`,`img_url` FROM `nn_product` WHERE `id`='.$k;
					$rs = mysqli_query($link,$sql);
					$r = mysqli_fetch_assoc($rs);
					
					$total = $total + ($v*$r['price']) ;
					
				?>
                <ul class="cartlist gray">
                	<li class="remove txt"><?=$i++?></li>
                    <li class="thumb"><a href="?mod=detail.html"><img src="images/sanpham/<?=$r['img_url']?>" alt="" ></a></li>
                    <li class="title txt"><a href="detail.html"><?=$r['name']?></a></li>
                    <li class="price txt"><?=number_format($r['price'],0)?></li>
                    <li class="qty txt"><?=$v?></li>
                    <li class="total txt"><?=number_format($v*$r['price'])?></li>
                </ul>
				<?php } ?>
               <!-- <ul class="cartlist">
                	<li class="remove txt"><a href="#"><img src="images/delete.gif" alt="" ></a></li>
                    <li class="thumb"><a href="detail.html"><img src="images/cart_thumb.gif" alt="" ></a></li>
                    <li class="title txt"><a href="detail.html">Alexander Christie</a></li>
                    <li class="price txt">$577.00</li>
                    <li class="qty"><input name="qty" type="text" value="1" ></li>
                    <li class="total txt">$577.00</li>
                </ul>
                <ul class="cartlist gray">
                	<li class="remove txt"><a href="#"><img src="images/delete.gif" alt="" ></a></li>
                    <li class="thumb"><a href="detail.html"><img src="images/cart_thumb.gif" alt="" ></a></li>
                    <li class="title txt"><a href="detail.html">Alexander Christie</a></li>
                    <li class="price txt">$577.00</li>
                    <li class="qty"><input name="qty" type="text" value="1" ></li>
                    <li class="total txt">$577.00</li>
                </ul>
                <ul class="cartlist">
                	<li class="remove txt"><a href="#"><img src="images/delete.gif" alt="" ></a></li>
                    <li class="thumb"><a href="detail.html"><img src="images/cart_thumb.gif" alt="" ></a></li>
                    <li class="title txt"><a href="detail.html">Alexander Christie</a></li>
                    <li class="price txt">$577.00</li>
                    <li class="qty"><input name="qty" type="text" value="1" ></li>
                    <li class="total txt">$577.00</li>
                </ul>
                <ul class="cartlist gray">
                	<li class="remove txt"><a href="#"><img src="images/delete.gif" alt="" ></a></li>
                    <li class="thumb"><a href="detail.html"><img src="images/cart_thumb.gif" alt="" ></a></li>
                    <li class="title txt"><a href="detail.html">Alexander Christie</a></li>
                    <li class="price txt">$577.00</li>
                    <li class="qty"><input name="qty" type="text" value="1" ></li>
                    <li class="total txt">$577.00</li>
                </ul>-->
                <div class="clear"></div>
                <div class="subtotal">
        
                    <!--<button type="submit">Update</button>-->
                	<h3 class="colr"><?=number_format($total)?></h3>
                </div>
                <div class="clear"></div>
               <!-- <div class="sections">
                	<div class="cartitems">
                    	<h6 class="colr">Based on your selection, you may be interested in the following items:</h6>
                        <ul>
                        	<li>
                            	<div class="thumb">
                                	<a href="detail.html"><img src="images/prod_cart.gif" alt="" ></a>
                                </div>
                                <div class="desc">

                                	<a href="#" class="title bold">Alexander Christie</a>
                                    <p class="bold">$300</p>
                                    <a href="#" class="simplebtn"><span>Add to Cart</span></a>
                                    <div class="clear"></div><br >
                                    <a href="#"><u>Add to Wishlist</u></a><br >
                                    <a href="#"><u>Add to Compare</u></a>
                                </div>
                            </li>
                            <li>
                            	<div class="thumb">
                                	<a href="detail.html"><img src="images/prod_cart.gif" alt="" ></a>
                                </div>
                                <div class="desc">
                                	<a href="detail.html" class="title bold">Alexander Christie</a>
                                    <p class="bold">$300</p>
                                    <a href="cart.html" class="simplebtn"><span>Add to Cart</span></a>
                                    <div class="clear"></div><br >
                                    <a href="#"><u>Add to Wishlist</u></a><br >
                                    <a href="#"><u>Add to Compare</u></a>
                                </div>
                            </li>
                        </ul>
                        <div class="clear"></div>
                        <div class="sec_botm">&nbsp;</div>
                    </div>
                    <div class="centersec">
                    <div class="discount">
                    	<h6 class="colr">Discount Codes</h6>
                        <p>Enter your coupon code if you have one.</p>
                        <ul>
                        	<li><input name="discount" type="text" class="bar" ></li>
                            <li><a href="#" class="simplebtn"><span>Apply Coupon</span></a></li>
                        </ul>
                        <div class="clear"></div>
                        <div class="sec_botm">&nbsp;</div>
                    </div>
                  <div class="estimate">
                    	<h6 class="colr">Estimate Shipping and Tax</h6>
                    <p>Enter your destination to get a shipping estimate.</p>
                      <ul>
                       	  <li class="bold">Country</li>
                          <li>
                          	<select name="jumpMenu" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)" class="bar">
                            	<option>item1</option>
                            	<option>item2</option>
                            	<option>item3</option>
                          	</select>
                          </li>
                      </ul>
                      <ul>
                       	  <li class="bold">State/Province</li>
                          <li>
                          	<select name="jumpMenu" id="jumpMenu1" onchange="MM_jumpMenu('parent',this,0)" class="bar">
                            	<option>item1</option>
                            	<option>item2</option>
                            	<option>item3</option>
                          	</select>
                          </li>
                      </ul>
                      <ul>
                       	  <li class="bold">Zip code</li>
                          <li><input name="discount" type="text" class="bar" ></li>
                          <li><a href="#" class="simplebtn"><span>Submit</span></a></li>
                      </ul>
                      <div class="clear"></div>
                        <div class="sec_botm">&nbsp;</div>
                    </div>
                    </div>
                    <div class="grand_total">
                    	<ul>
                        	<li class="title">Subtotal</li>
                            <li class="price bold">$349.99</li>
                        </ul>
                        <ul>
                        	<li class="title"><h5>Grand total</h5></li>
                            <li class="price"><h5>$349.99</h5></li>
                        </ul>
                        <div class="clear"></div>
                        <a href="#" class="proceed right">Proceed to Checkout</a>
                        <div class="clear"></div>
                        <a href="#" class="right">Checkout with Multiple Addresses</a>
                        <div class="clear"></div>
                        <div class="sec_botm">&nbsp;</div>
                    </div>
                </div>-->
             <h2 class="heading colr">SHIPPING INFOR</h2> 
             <p class='error' align='center'><?=$msg?></p>
           	 <form action="" method="post" id="checkout">
                        <ul class="forms">
                        	<li class="txt">Full Name <span class="req">*</span></li>
                            <li class="inputfield"><input type="text" name="name" class="bar" value="<?=$r_user['name']?>"></li>
                        </ul>
                          <ul class="forms">
                        	<li class="txt">Mobile <span class="req">*</span></li>
                            <li class="inputfield"><input type="text" name="mobile" class="bar" value="<?=$r_user['mobile']?>"></li>
                        </ul>
                        <ul class="forms">
                        	<li class="txt">Email <span class="req">*</span></li>
                            <li class="inputfield"><input type="text" name="email" class="bar" value="<?=$r_user['email']?>"></li>
                        </ul>
                          <ul class="forms">
                        	<li class="txt">Address <span class="req">*</span></li>
                            <li class="textfield"><textarea name="address"><?=$r_user['address']?></textarea></li>
                        </ul>                      
	 					<ul class="forms">
                        	<li class="txt">Remark <span class="req">*</span></li>
                            <li class="textfield"><textarea name="remark" id="remark"></textarea></li>
                        </ul>
                        <ul class="forms">
                        	<li class="txt">&nbsp;</li>

                            <!--<a href="#" class="forgot">Forgot Your Password?</a></li>-->
                        </ul>
                        </form>
                        <div class="clear"></div>
                        <a href="?mod=listing" class="simplebtn"><span>Continue Shopping</span></a>
                    	<a href="?mod=cart" class="simplebtn"><span>Update Cart</span></a>
                        <a href="#" onclick="$('#checkout').submit()" class="simplebtn"><span>Check out</span></a>
            </div>
                <div class="clear"></div>
            