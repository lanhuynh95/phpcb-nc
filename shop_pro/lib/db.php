<?php
	//Chuyen sang chế độ development => Show tat ca loi, Warning
	error_reporting(E_ALL);
	//Chuyen sang chế độ production => Hide tat ca loi, warning
	
	//error_reporting(0);
	
	//Ket noi toi MySQL
	define('HOST','localhost');
	define('USER','root');
	define('PASS','Root@123456');
	define('DB_NAME','shop');
	
	$link = mysqli_connect(HOST,USER,PASS,DB_NAME) or die('Lỗi kết nối');
	
	//Đồng bộ charset
	mysqli_set_charset($link,'utf8');
?>