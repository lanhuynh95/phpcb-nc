<?php

	unset($_SESSION['id']);
	unset($_SESSION['name']);
	
	header('location:?mod=login');
 
?>