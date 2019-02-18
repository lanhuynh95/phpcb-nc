<?php 
//$_SESSION['cart'] = array(2 => 10,10 => 50,5 => 150,7 => 350);


$cart =  $_SESSION['cart'];

$id = $_GET['id'];

$act = $_GET['act'];

if( $act == 1) //Them
	{
	$qty = intval($_GET['qty']);
    if($qty<1) $qty=1;
	
	$cart[$id] += $qty;
	}
if( $act == 2) //Xoa
	unset($cart[$id]);
	
if( $act == 3) //Sua
	{
		//print_r($_POST);
		//print_r($cart);
			
			foreach($cart as $k => $v)
			{
				$qty = intval($_POST[$k]);
				$cart[$k] = $qty >0?$qty:1;
			}
		
	}

$_SESSION['cart'] = $cart;

header('location:?mod=cart');
?>
