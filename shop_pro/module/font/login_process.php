<?php 
	$email = $_POST['email'];
	$pass = hash('sha512',$_POST['pass']);
	
	echo $sql=" SELECT `id`,`name` FROM `nn_user` WHERE `email` = '$email' AND `password` = '$pass'";
	$rs=mysqli_query($link,$sql);
	
	if(mysqli_num_rows($rs) == 0)
		echo 'Email hoặc Mật khẩu Sai';
	else 
	{
		$r=mysqli_fetch_assoc($rs);
		
		$_SESSION['id']=$r['id'];
		$_SESSION['name']=$r['name'];
		
		echo 'Đăng nhập thành công';
		
		header('location:?mod=home');
	}
		
?>	