<?php
	include('header.php');

	//insertion de l'ensemble des images du dossier 'images'
	$fichiers = scandir('images');
	$index = 5;
	
	/*
	foreach($fichiers as $image){
		if($image[0]!='.'){
		$db->exec("INSERT INTO image VALUES(NULL,'Image-$index',NOW(),'Korell','Votre description','$image')");
		$index++;
		}
	}
	*/
	

	/*
	for($i=9; $i<32; $i++){
		$index = $i-8;
		$db->exec("UPDATE image SET titre='Image-$index', id=$index WHERE id=$i");
	}
	*/
