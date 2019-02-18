<?php
 	session_start();
	ob_start();
	include('lib/db.php');
	
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Estore 16</title>
<link rel="stylesheet" type="text/css" href="lib/font-awesome-4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="css/admin_style.css">
<script type="text/javascript" src="js/jquery-1.12.4.min.js"></script>
</head>
<body>
	<div id="top-menu" >
		<ul>
		 <?php
			if(!isset($_SESSION['ad_name']))
				echo " Chào Bạn ! Vui lòng <a href='?mod=login'>Đăng Nhập</a>";
			else {
				echo "Xin Chào <b style='color:greenyellow'>".$_SESSION['ad_name']."</b>!<a href='?mod=logout' style='margin-left:10px'> Đăng xuất</a>";
				}
			?>
		</ul>
	</div>
	<div class="clear"></div>
	<div id="banner"></div>
	<div class="clear"></div>	
	<div id="menu">
	    <div class="menu-content"><a href="?mod=home"><i class="fa fa-home fa-2x"></i><br>Trang Chủ</a></div>
	    <div class="menu-content"><a href="?mod=dept"><i class="fa fa-newspaper-o fa-2x"></i><br>Chủng Loại</a></div>
	    <div class="menu-content"><a href="?mod=cate"><i class="fa fa-bars fa-2x"></i><br>Loại SP</a></div>
	    <div class="menu-content"><a href="?mod=prod"><i class="fa fa-book fa-2x"></i><br>Sản Phẩm</a></div>
	</div>
	<div class="clear"></div>
	<div id="wrapper">	
		<?php
				if(!empty($_GET['mod']))
					$mod = $_GET['mod'];
				else
					$mod = 'home';
				if(!isset($_SESSION['ad_id']))
					$mod = 'login';
				include("module/back/{$mod}.php");
		?>
	</div>
	<div class="clear"></div>
<div id=footer>
		<div class="foot_logo">
	    	<a href="#"><img style="margin-left: 150px" src="images/logo1.jpg" alt="" ></a>
	    </div>
	    <div class="botm_navi">
	        <ul style="margin-right: 100px">
	        	<li>Thông tin liên hệ</li>
	            <li>Số Điện Thoại: 01230 012312</li>
	            <li>Email: <a href="mailto:info@abc.com">info@abc.com</a></li>
	            <li><a href="#">Bản Đồ</a></li>
	            <li><a href="#">Chi Nhánh 1: Đường A, Phường 1, Quận 1, Thành Phố Hồ Chính Minh</a></li>
	            <li><a href="#">Chi Nhánh 2: Đường B, Phường 2, Quận 2, Thành Phố Hồ Chính Minh</a></li>
	            <li><a href="#">Chi Nhánh 3: Đường C, Phường 3, Quận 3, Thành Phố Hà Nội</a></li>
	            <li><a href="#">Chi Nhánh 4: Đường D, Phường 4, Quận 4, Thành Phố Hà Nội</a></li>
	            <li><a href="#">Chi Nhánh 5: Đường E, Phường 5, Quận 5, Thành Phố Đà Nẵng</a></li>
	            <li><a href="#">Chi Nhánh 6: Đường F, Phường 6, Quận 6, Thành Phố Đà Nẵng</a></li>
	        </ul>
	    </div>
</div>
</div>
</body>
</html>
