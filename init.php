<?php
	session_start();
	include('config.php');
	include('functions.php');
	$db = connection();
	$img_par_page = $config['nb_images_page'];
