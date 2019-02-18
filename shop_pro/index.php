<?php
 	session_start();
	ob_start();
	include('lib/db.php');
	
	
	//Lay danh sach chung loai
	$sql = 'SELECT `id`,`name` FROM `nn_department` WHERE `active` = 1 ORDER BY `order`';
	$rs_dept = mysqli_query($link, $sql);
	
	
?>
<!DOCTYPE html>
<head>
<meta charset="utf-8">
<title>Estore 16</title>
<!-- // Stylesheets // -->
<link rel="stylesheet" href="css/style.css" type="text/css" >
<link rel="stylesheet" href="css/nivo-slider.css" type="text/css" media="screen" >
<link rel="stylesheet" href="css/default.advanced.css" type="text/css" >
<link rel="stylesheet" href="css/contentslider.css" type="text/css"  >
<link rel="stylesheet" href="css/jquery.fancybox-1.3.1.css" type="text/css" media="screen" >
<link rel="stylesheet" type="text/css" href="lib/font-awesome-4.7.0/css/font-awesome.min.css">
<script type="text/javascript" src="js/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="js/jquery.easing.1.2.js"></script>
<script type="text/javascript" src="js/jcarousellite_1.0.1.js"></script>
<script type="text/javascript" src="js/scroll.js"></script>
<script type="text/javascript" src="js/ddaccordion.js"></script>
<script type="text/javascript" src="js/acordn.js"></script>
<script type="text/javascript" src="js/contentslider.js"></script>
<script type="text/javascript" src="js/jquery.fancybox-1.3.1.js"></script>
<script type="text/javascript" src="js/lightbox.js"></script>
</head>
<body>
<a name="top"></a>
<div id="wrapper_sec">
	<!-- Header -->
	<div id="masthead">
    	<div class="secnd_navi">
        	<ul class="links">
            	<li>
                <?php
				if(!isset($_SESSION['name']))
					echo " Chào Bạn !<br>";
				else 
					echo "Xin Chào ".$_SESSION['name'];
				 ?>
                </li>
                <?php if(isset($_SESSION['name'])) { ?>
                <li><a href="?mod=account">Tài khoản</a></li>
                <?php } ?>
                <li><a href="?mod=cart">Xe hàng</a></li>
                <li><a href="?mod=checkout">Đặt hàng</a></li>
                <li class="last">
                <?php
				if(!isset($_SESSION['id']))
                	echo "<a href='?mod=login'>Đăng nhập</a>";
				else
					echo "<a href='?mod=logout'>Đăng xuất</a>";
				?>
                </li>
            </ul>
            <form action="?mod=search" method="POST">
             <ul class="search">
            <li><input type="search" placeholder="Bạn tìm gì ... ?" id="searchBox" name="search"  class="bar" value="<?php if(isset($_POST['search'])) echo $_POST['search'] ?>"></li>
            <li><button type="submit" id="search"></button></li>
            </ul>
            </form>
        </div>
        <div class="clear"></div>
    	<div class="logo">
        	
        </div>
       
        <div class="clear"></div>
        <div class="navigation">
            <ul id="nav" class="dropdown dropdown-linear dropdown-columnar">
                 <li><a href="index.php"> <span class="fa fa-home fa-2x" style="margin:0px 0px 0px 20px"></span> <br> Trang chủ</a></li>
                <li><a href="#"><span class="fa fa-newspaper-o fa-2x"  style="margin:0px 0px 0px 20px"></span> <br>Giới thiệu</a></li>
                <li class="dir"><a href="?mod=search"><span class="fa fa-bars fa-2x"  style="margin:0px 0px 0px 20px"></span> <br>Sản phẩm</a>
                    <ul class="bordergr big">
                    <?php
						while($r = mysqli_fetch_assoc($rs_dept))
						{
					?>
                        <li class="dir"><span class="colr navihead bold"><?=$r['name']?></span>
                            <ul>
                            <?php
								//Lay cac loai sp thuoc chung loai
								$sql = 'SELECT `id`,`name` FROM `nn_category` WHERE `active`= 1 AND `department_id`='.$r['id'].' ORDER BY `id`';
								$rs_cate = mysqli_query($link, $sql);
								while($r = mysqli_fetch_assoc($rs_cate))
								{
							?>
                                	<li><a href="?mod=listing&cid=<?=$r['id']?>"><?=$r['name']?></a></li>
                            <?php
								}
							?>
                            </ul>
                        </li>
                    <?php
						}
					?>
                    </ul>
                </li>
                <li class="dir"><a href="index.php"><span class="fa fa-book fa-2x" style="margin:0px 0px 0px 20px"></span><br>Trang</a>
                    <ul class="bordergr small">
                        <li class="dir"><span class="colr navihead bold">Danh Sách</span>
                            <ul>
                                <li class="clear"><a href="index.php">Trang Chủ</a></li>
                                <li class="clear"><a href="?mod=listing">Sản Phẩm</a></li>
                                <li class="clear"><a href="?mod=contact">Liên Hệ</a></li>
                                <li class="clear"><a href="?mod=account">Tài Khoản</a></li>
                                <li class="clear"><a href="?mod=cart">Xe Hàng</a></li>
                                <li class="clear"><a href="?mod=checkout">Đặt Hàng</a></li>
                                <li class="clear"><a href="?mod=login">Đăng nhập</a></li>
                                <li class="clear"><a href="?mod=search">Tìm Kím</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li><a href="?mod=contact"><span class="fa fa-phone-square fa-2x" style="margin:0px 0px 0px 20px"></span><br>Liên hệ</a></li>
            </ul>
            <ul class="lang">
            	<li style="color: green">Chia sẻ thông tin:</li>
                <li><a href="#"><img src="images/linkdin.gif" alt="" ></a></li>
                <li><a href="#"><img src="images/twitter.gif" alt="" ></a></li>
                <li><a href="#"><img src="images/facebook.gif" alt="" ></a></li>
            </ul>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
    <div class="clear"></div>
    <!-- Scroolling Products -->
    <div class="content_sec">
    	<!-- Column2 Section -->
        <div class="col2">
        	<div class="col2_top">&nbsp;</div>
            <div class="col2_center">
            <?php
				if(!empty($_GET['mod']))
					$mod = $_GET['mod'];
				else
					$mod = 'home';
			
				include("module/font/{$mod}.php");
			?>
              </div>
            <div class="clear"></div>
            <div class="col2_botm">&nbsp;</div>
        </div>
        <!-- Column1 Section -->
    	<div class="col1">
        	<!-- Categories -->
                <div class="category">
                	<div class="col1center">
                    <div class="small_heading">
                        <h5>Thể Loại</h5>
                    </div>
                    <div class="glossymenu">
                     <?php
					 	mysqli_data_seek($rs_dept,0);
						while($r = mysqli_fetch_assoc($rs_dept))
						{
					?>
                        <a class="menuitem submenuheader" href="#" ><?=$r['name']?></a>
                        <div class="submenu">
                            <ul>
                            <?php
								//Lay cac loai sp thuoc chung loai
								$sql = 'SELECT `id`,`name` FROM `nn_category` WHERE `active`= 1 AND `department_id`='.$r['id'].' ORDER BY `order`';
								$rs_cate = mysqli_query($link, $sql);
								while($r = mysqli_fetch_assoc($rs_cate))
								{
							?>
                                	<li><a href="?mod=listing&cid=<?=$r['id']?>"><?=$r['name']?></a></li>
                            <?php
								}
							?>  
                                
                            </ul>
                        </div>
                    <?php
						}
					?>
                    </div>
                    </div>
                    <div class="clear"></div>
                    <div class="left_botm">&nbsp;</div>
                </div>
                <!-- My Cart Products -->
                <div class="mycart">
                	<div class="col1center">
                    <div class="small_heading">
                        <h5>Xe Hàng   <span class="fa fa-cart-plus"></span></h5></h5>
                        <div class="clear"></div>
                    </div>
                    <ul>
                        <?php
                        if(isset($_SESSION['cart'])) {
                        $cart= $_SESSION['cart'] ;
                    } else {
                        $_SESSION['cart']=array(); 
                         $cart= $_SESSION['cart'] ;
                    };

						$total=0;
                        $d=0;
                        foreach($cart as $k => $v)
						{
						$sql='SELECT `name`,`price`,`id`,`qty` FROM `nn_product` WHERE `id`='.$k;
						$rs = mysqli_query($link,$sql);
						$r = mysqli_fetch_assoc($rs); 
						
                        $d=$d + $v;
						$total = $total + ($v*$r['price']) ;
                        ?> 
                        <li>
                           
                            <p class="bold title">
                                <a href="?mod=" style="color: blue"><?=$r['name']?></a>
                            </p>
                            <div class="grey">
                                <p class="left">QTY: <span class="bold"><?=$v?></span></p>
                                <p class="right">Price: <span class="bold"><?=number_format($v*$r['price'])?></span></p>
                            </div>
                            
                        </li>
                        <?php } ?>
                       
                    </ul>
                    <span class="veiwitems"> (<?=$d?>) Items - <a href="?mod=cart" class="colr">Xem Giỏ Hàng</a></span>
                    <p class="right bold sub">Tổng: <?=number_format($total)?></p>
                    <div class="clear"></div>
                    <a href="?mod=checkout" class="simplebtn right"><span>Xem</span></a>
                    </div>
                    <div class="clear"></div>
                    <div class="left_botm">&nbsp;</div>
                </div>
                <div class="poll">
                <div class="col1center">
            	<div class="small_heading">
            		<h5>Sản Phẩm</h5>
                </div>
                <p>Có thể bạn sẽ thích | Sán phẩm bất kỳ</p><br>
                <ul style="text-align: center;">
                    
                        <?php 
                            $random2=rand(492,652);
                            $sql = "SELECT `img_url`, `name`,`id`
                                FROM `nn_product` 
                                WHERE `active` = 1 AND `id`=$random2";
                            $rs_rand = mysqli_query($link, $sql);
                            $r=mysqli_fetch_assoc($rs_rand);
                        ?>
                    <li><?=$r['name']?></li>
                    <li>
                	   <a href="?mod=detail&id=<?=$r['id']?>"><img src="images/sanpham/<?=$r['img_url']?>" width="100px"></a>
                    </li>
                </ul>
                <a href="?mod=detail&id=<?=$r['id']?>" class="simplebtn"><span>Xem Thêm</span></a>
                </div>
                <div class="clear"></div>
                    <div class="left_botm">&nbsp;</div>
            </div>
            <div class="col1center">
            <div class="small_heading">
                    <h5>Sự Kiện Nổi Bật</h5>
                </div>
               <?php 
                    $random1=rand(1,2);
                    $sql = "SELECT `img_url`, `name`
                        FROM `nn_poster` 
                        WHERE `active` = 1 AND id=$random1";
                    $rs_new = mysqli_query($link, $sql);
                    $r=mysqli_fetch_assoc($rs_new);
                ?>
                <a href="?mod=detail">
                    <img src="images/poster/<?=$r['img_url']?>" alt="<?=$r['name']?>" width="200px">
                </a>
            <div class="clear"></div>
         </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<!-- Footer Section -->
	<div id="footer">
    	<div class="foot_inr">
        <div class="foot_top">
        	<div class="foot_logo">
            	<a href="#"><img src="images/logo1.jpg" alt="" ></a>
            </div>
            <div class="botm_navi">
                <ul>
                	<li>Thông tin liên hệ</li>
                    <li>Số Điện Thoại: 01230 012312</li>
                    <li>Email: <a href="mailto:info@abc.com">info@abc.com</a></li>
                    <li><a href="https://www.google.com/maps">Bản Đồ</a></li>
                    <li><a href="#">Chi Nhánh 1: Đường A, Phường 1, Quận 1, Thành Phố Hồ Chính Minh</a></li>
                    <li><a href="#">Chi Nhánh 2: Đường B, Phường 2, Quận 2, Thành Phố Hồ Chính Minh</a></li>
                    <li><a href="#">Chi Nhánh 3: Đường C, Phường 3, Quận 3, Thành Phố Hà Nội</a></li>
                    <li><a href="#">Chi Nhánh 4: Đường D, Phường 4, Quận 4, Thành Phố Hà Nội</a></li>
                    <li><a href="#">Chi Nhánh 5: Đường E, Phường 5, Quận 5, Thành Phố Đà Nẵng</a></li>
                    <li><a href="#">Chi Nhánh 6: Đường F, Phường 6, Quận 6, Thành Phố Đà Nẵng</a></li>
                </ul>
            </div>
        </div>
        <div class="foot_bot">
        	<div class="emailsignup">
        	<h5>Join Our Mailing List</h5>
            <ul class="inp">
            	<li><input name="newsletter" type="text" class="bar" ></li>
                <li><a href="#" class="signup">Signup</a></li>
            </ul>
            <div class="clear"></div>
        </div>
            <div class="botm_links">
            	<ul>
                	<li class="first"><a href="#">Home</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Privacy</a></li>
                </ul>
                <div class="clear"></div>
                <p>© 2010 DUMY. All Rights Reserved</p>
            </div>
            <div class="copyrights">
        	<p>
            	Hội sở: ABC DEF MNG
            </p>
        </div>
        <div class="clear"></div>
        </div>
        <div class="clear"></div>
        <div class="topdiv">
        	<a href="#top" class="top">Top</a>
        </div>
        </div>
    </div>
</body>
</html>