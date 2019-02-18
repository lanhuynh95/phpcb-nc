
<?php

if(!isset($_SESSION['id'])) header('location:?mod=login');

$id_user=$_SESSION['id'];




	$msg="";
	//Nếu như user đã submit
	if(isset($_POST['name']))
	{
		$name = $_POST['name'];
		$pass = $_POST['pass'];
		$repass = $_POST['repass'];
		$mobile = $_POST['mobile'];
		$address = $_POST['address'];
		$dob = $_POST['dob'];
		
		$dob = date('Y-m-d',strtotime($dob));
		
		$gender = $_POST['gender'];
		
		//Kiểm tra data
		if($name == '')
			$msg = 'Bạn phải nhập họ tên';	
		elseif (strlen($pass) > 0 && strlen($pass) < 8)
			$msg = 'Mật khẩu tối thiểu 8 ký tự';
		elseif ($pass != $repass)
			$msg = 'Mật khẩu nhập lại không đúng';
		else //Hợp lệ
		{
			if($pass != "") {		
			$pass = hash('sha512',$pass);
			$sql = "UPDATE `nn_user` SET `name`='$name', `password`='$pass', `mobile`='$mobile', `address`='$address',`dob`='$dob',`gender`='$gender' WHERE `id`=$id_user"; }
			else {
				$sql = "UPDATE `nn_user` SET `name`='$name', `mobile`='$mobile', `address`='$address',`dob`='$dob',`gender`='$gender' WHERE `id`=$id_user";}
			
			$rs=mysqli_query($link,$sql);
			if($rs)
				$msg = 'đăng ký thành công';
				else
				$msg = 'đăng ký không thành công';
		}
	}
	
	$sql='SELECT * FROM `nn_user` WHERE `id`='.$id_user;
	$rs=mysqli_query($link,$sql);
	$r=mysqli_fetch_assoc($rs);
?>

                <h2 class="heading colr">UPDATE</h2>
                <div class="login">
                	<div class="registrd">
                    	<h3>Please Sign Up</h3>
                        
                        <p class='error' align='center'><?=$msg?></p>
                        
                        <form action="" method="post" id="update">
                        <ul class="forms">
                        	<li class="txt">Full Name <span class="req">*</span></li>
                            <li class="inputfield"><input type="text" name="name" class="bar" value="<?=$r['name']?>"></li>
                        </ul>
                        
                        <ul class="forms">
                        	<li class="txt">Password <span class="req">*</span></li>
                            <li class="inputfield"><input type="password" name="pass" class="bar" placeholder="Tối thiểu 8 ký tự"></li>
                        </ul>
                        <ul class="forms">
                        	<li class="txt">Retype Password <span class="req">*</span></li>
                            <li class="inputfield"><input type="password" name="repass" class="bar" ></li>
                        </ul>
                         <ul class="forms">
                        	<li class="txt">Mobile <span class="req">*</span></li>
                            <li class="inputfield"><input type="text" name="mobile" class="bar" value="<?=$r['mobile']?>"></li>
                        </ul>
                        <ul class="forms">
                        	<li class="txt">DOB <span class="req">*</span></li>
                            <li class="inputfield"><input readonly type="text" name="dob" id="dob" class="bar" value="<?=date('d-m-Y',strtotime($r['dob']))?>"></li>
                        </ul>
                        <ul class="forms">
                        	<li class="txt">Gender <span class="req">*</span></li>
                            <li class="">
                            	<input <?php if($r['gender'] == 1) echo 'checked' ?> type="radio" name="gender" value="1"> Nam
                                <input <?php if($r['gender'] == 0) echo 'checked' ?> type="radio" name="gender" value="0"> Nữ
                            </li>
                        </ul>
                        <ul class="forms">
                        	<li class="txt">Address <span class="req">*</span></li>
                            <li class="textfield"><textarea name="address"><?=$r['address']?></textarea></li>
                        </ul>
                        <ul class="forms">
                        	<li class="txt">&nbsp;</li>
                            <li><a href="#" onClick="$('#update').submit()" class="simplebtn"><span>Update</span></a>
                            <li><a href="?mod=login" class="simplebtn"><span>Home</span></a><br> 
                            <!--<a href="#" class="forgot">Forgot Your Password?</a></li>-->
                        </ul>
                        </form>
                    </div>
                   <!-- <div class="newcus">
                    	<h3>Please Sign In</h3>
                        <p>
                        	By creating an account with our store, you will be able to move through the checkout process faster, store multiple shipping addresses, view and track your orders in your account and more.
                        </p>
                        <a href="#" class="simplebtn"><span>Register</span></a>
                    </div>-->
                </div>
                <div class="clear"></div>
    
 	<link href="js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
	<script type="text/javascript" src="js/jquery-ui/jquery-ui.min.js"></script>
 
 <script>
  $( function() {
    $( "#dob" ).datepicker({
	  dateFormat: 'dd-mm-yy',
      changeMonth: true,
      changeYear: true,
	  yearRange:'-99:+0',
    });
  } );
  </script>
          