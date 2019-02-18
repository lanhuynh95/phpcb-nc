<?php 
	if(isset($_SESSION['ad_id'])) header('location:?mod=home');
	$error="";
	if(isset($_POST['email'])) {
		$email=$_POST['email'];
		$password=hash('sha512',$_POST['password']);
		$repass=hash('sha512',$_POST['repass']);

		$sql="SELECT * FROM `nn_admin` WHERE `email`='$email' AND `password`='$password'";
		$rs=mysqli_query($link,$sql);

		if($email=="") $error ="Vui Lòng nhập email"; 
		else if($repass!=$password) $error ="Mật khẩu không trùng khớp"; 
		else if(mysqli_num_rows($rs) == 0)
		$error='Email hoặc Mật khẩu Sai';
		else 
		{
		$r=mysqli_fetch_assoc($rs); 

		$_SESSION['ad_id']=$r['id'];
		$_SESSION['ad_name']=$r['name'];
		
		echo 'Đăng nhập thành công';
		header('location:?mod=home');
		}
	}
?>
		<div id="content">
			<div id="text-center">
				<div id="icon-content"><i class="fa fa-user fa-5x" aria-hidden="true"></i></div>
				<div id="text-content">Vui lòng đăng nhập tài khoản</div>
				<div id="text-content" style="color: gray">Trang quản trị</div>
				<div id="text-content" style="color: red"><?=$error?></div>
			</div>
		</div>
		<form action="" method="POST">
		<div class="form-login">
			<span class="login-icon"><i class="fa fa-envelope"></i></span>
			<input type="text" class="login" placeholder="Email" name="email" required>
		</div>
		<div class="form-login">
			<span class="login-icon"><i class="fa fa-unlock-alt"></i></span>
			<input type="password" class="login" placeholder="Password" name="password" required>
		</div>
		<div class="form-login">
			<span class="login-icon"><i class="fa fa-lock"></i></span>
			<input type="password" class="login" placeholder="Lock" name="repass" required>
		</div>
		<button type="submit" name="Login" class="but-login">Đăng nhập</button>
		</form>