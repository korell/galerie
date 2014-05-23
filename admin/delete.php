<?php 
	include('../init.php');
	include('header-admin.php');
	$args = '';
	if(isset($_GET['imgid'])){
		$imgid = htmlspecialchars($_GET['imgid']);
		deleteImage($imgid);
		$args = '?suppr=ok';
	}
	
	if(isset($_GET['orderby']) && isset($_GET['dir'])){
		$orderby = htmlspecialchars($_GET['orderby']);
		$dir = htmlspecialchars($_GET['dir']);
		$args .= '&orderby='.$orderby.'&dir='.$dir.'&imgid='.$imgid;
	}
	$location = 'Location: index.php'.$args;
	header($location);