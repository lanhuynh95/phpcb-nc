<?php 
if(!isset($_SESSION['id'])) header('location:?mod=login');

$id_user = $_SESSION['id'];
$order_id=$_GET['id'];

$msg="";
 


//
$sql="SELECT `img_url`,`name`,od.`price` ,od.`qty`
		FROM `nn_product` p,`nn_order_detail` od
		WHERE p.id=od.product_id AND `order_id`=$order_id";
		$rs_order = mysqli_query($link,$sql);

$sql='SELECT * FROM `nn_order` WHERE `id`='.$order_id;
$rs=mysqli_query($link,$sql);
$r_user=mysqli_fetch_assoc($rs);

if($id_user != $r_user['user_id']) {header('location:?mod=account');} 


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
					$i=1;
					$total=0;
					while($r = mysqli_fetch_assoc($rs_order)) {
					$total = $total + ($r['qty']*$r['price']) ;	
				?>
                <ul class="cartlist gray">
                	<li class="remove txt"><?=$i++?></li>
                    <li class="thumb"><a href="?mod=detail.html"><img src="images/sanpham/<?=$r['img_url']?>" alt="" ></a></li>
                    <li class="title txt"><a href="detail.html"><?=$r['name']?></a></li>
                    <li class="price txt"><?=number_format($r['price'],0)?></li>
                    <li class="qty txt"><?=$r['qty']?></li>
                    <li class="total txt"><?=number_format($r['qty']*$r['price'])?></li>
                </ul>
				<?php } ?>
                <div class="clear"></div>
                <div class="subtotal">
        
                    <!--<button type="submit">Update</button>-->
                	<h3 class="colr"><?=number_format($total)?></h3>
                </div>
                <div class="clear"></div>
             <h2 class="heading colr">SHIPPING INFOR</h2> 
             <p class='error' align='center'><?=$msg?></p>
           
                        <ul class="forms">
                        	<li class="txt">Full Name <span class="req">*</span></li>
                            <li class="inputfield"><input name="name" type="text" disabled class="bar" value="<?=$r_user['name']?>"></li>
                        </ul>
                          <ul class="forms">
                        	<li class="txt">Mobile <span class="req">*</span></li>
                            <li class="inputfield"><input disabled type="text" name="mobile" class="bar" value="<?=$r_user['mobile']?>"></li>
                        </ul>
                        <ul class="forms">
                        	<li class="txt">Email <span class="req">*</span></li>
                            <li class="inputfield"><input disabled type="text" name="email" class="bar" value="<?=$r_user['email']?>"></li>
                        </ul>
                          <ul class="forms">
                        	<li class="txt">Address <span class="req">*</span></li>
                            <li class="textfield"><textarea disabled name="address"><?=$r_user['address']?></textarea></li>
                        </ul>                      
	 					<ul class="forms">
                        	<li class="txt">Remark <span class="req">*</span></li>
                            <li class="textfield"><textarea disabled name="remark" id="remark"></textarea></li>
                        </ul>
                        <ul class="forms">
                        	<li class="txt">&nbsp;</li>
                        </ul>
                       
                        <div class="clear"></div>
                        <a href="javascript:history.go(-1)" class="simplebtn"><span>Back</span></a>
            </div>
                <div class="clear"></div>
            