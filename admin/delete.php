<?php 
	require_once('../init.php');
	require_once('header-admin.php');
	$params = '';
	if(isset($_GET['imgid'])){
		$imgid = htmlspecialchars($_GET['imgid']);
		deleteImage($imgid);
		$query = $_SERVER['QUERY_STRING'];
		$params = majParamGet($query, ['suppr'=>'ok'], false);
	}
	
	$location = 'Location: index.php'.$params;
	header($location);