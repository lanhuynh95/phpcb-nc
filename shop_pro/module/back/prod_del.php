<?php
$id=$_GET['id'];		
	$sql="SELECT `img_url`,`category_id` FROM `nn_product` WHERE `id`=".$id;
	$rs_prod=mysqli_query($link,$sql);
	$r_prod=mysqli_fetch_assoc($rs_prod);
	if(is_file('images/sanpham/'.$r_prod['img_url'])) unlink('images/sanpham/'.$r_prod['img_url']);

	$sql = 'DELETE FROM `nn_product` WHERE `category_id`='.$id;
	mysqli_query($link, $sql);
	
	$sql="DELETE FROM `nn_product` WHERE `id`=".$id;
	mysqli_query($link,$sql);

 	header('location:?mod=prod&cid='.$r_prod['category_id'])
?>