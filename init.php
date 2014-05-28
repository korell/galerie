<?php
	session_start();
	require_once('config.php');
	require_once('functions.php');
	$db = connection();
	$img_par_page = $config['nb_images_page'];
