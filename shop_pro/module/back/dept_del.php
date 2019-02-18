<?php
$id=$_GET['id'];
	$sql="DELETE FROM `nn_department` WHERE id=".$id;
	$rs=mysqli_query($link,$sql);	
 	header('location:?mod=dept')
?>