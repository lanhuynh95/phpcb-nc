
<?php
	$msg="";
	//Nếu như user đã submit
	if(isset($_POST['name']))
	{
		$name = $_POST['name'];
		$email = $_POST['email'];
		$pass = $_POST['pass'];
		$repass = $_POST['repass'];
		$mobile = $_POST['mobile'];
		
		//Kiểm tra data
		if($name == '')
			$msg = 'Bạn phải nhập họ tên';
		elseif (filter_var($email,FILTER_VALIDATE_EMAIL) == false)
			$msg = 'Địa chỉ email không hợp lệ';
		elseif (strlen($pass) < 8)
			$msg = 'Mật khẩu tối thiểu 8 ký tự';
		elseif ($pass != $repass)
			$msg = 'Mật khẩu nhập lại không đúng';
		else //Hợp lệ
		{
			//Ma hoa password
			$pass = hash('sha512',$pass);
			
			$sql = "INSERT INTO `nn_user`(`name`,`email`,`password`,`mobile`) VALUES('$name','$email','$pass','$mobile')";
			$rs=mysqli_query($link, $sql);
			if($rs) {
				$msg = 'đăng ký thành công. Chuyển đến trang đăng nhập';
			
?>
			<script>
				setTimeout("window.location='?mod=login';",3000);//Chuyen trang sau 3s
			</script>
<?php
			}
			else
				$msg = 'Email đã tồn tại';
			
		}
	}
?>
                <h2 class="heading colr">Đăng Ký</h2>
                <div class="login">
                	<div class="registrd">
                    	<h3>Đăng Ký Tài Khoản</h3>
                        <p>Nếu bạn đã có tài khoản, hãy đăng nhập.</p>
                        
                        <p class='error' align='center'><?=$msg?></p>
                        
                        <form action="" method="post">
                        <ul class="forms">
                        	<li class="txt">Họ Tên <span class="req">*</span></li>
                            <li class="inputfield"><input type="text" name="name" class="bar" value="<?php if(!empty($name)) echo $name ?>"></li>
                        </ul>
                        <ul class="forms">
                        	<li class="txt">Email <span class="req">*</span></li>
                            <li class="inputfield"><input type="text" name="email" class="bar" value="<?php if(!empty($email)) echo $email ?>"></li>
                        </ul>
                        <ul class="forms">
                        	<li class="txt">Mật Khẩu <span class="req">*</span></li>
                            <li class="inputfield"><input type="password" name="pass" class="bar" placeholder="Tối thiểu 8 ký tự"></li>
                        </ul>
                        <ul class="forms">
                        	<li class="txt">Xác Nhận MK <span class="req">*</span></li>
                            <li class="inputfield"><input type="password" name="repass" class="bar" ></li>
                        </ul>
                         <ul class="forms">
                        	<li class="txt">Số Điện Thoại <span class="req">*</span></li>
                            <li class="inputfield"><input type="text" name="mobile" class="bar" value="<?php if(!empty($mobile)) echo $mobile ?>"></li>
                        </ul>
                        <ul class="forms">
                        	<li class="txt">&nbsp;</li>
                            <li><button type="submit">Đăng Ký</button> 
                            <li><a href="?mod=login"><button type="button">Đăng Nhập</button></a><br> 
                        </ul>
                        </form>
                    </div>
                </div>
                <div class="clear"></div>
          