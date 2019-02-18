<?php
	$id=$_GET['id'];
	
	$sql="UPDATE `nn_order` SET `status`=-1 WHERE id=".$id;
	$rs=mysqli_query($link,$sql);
		
 	header('location:?mod=account')
?>