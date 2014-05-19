<?php
	include('../init.php');
	include('header.php');
	$images = getListImg();
	$table = '<div class="admin"><table>';
	foreach ($images as $image){
		$date = new DateTime($image['date_ajout']);
		$date = $date->format('d/m/Y H:i:s');
		$table .= '<tr>';
		$table .= '<td><img src="../'.galerieImgDirectory().'/'.$image['nom_fichier'].'" alt="image" ></td>';
		$table .= '<td>'.$image['titre'].'</td>';
		$table .= '<td>'.$image['description'].'</td>';
		$table .= '<td>'.$image['auteur'].'</td>';
		$table .= '<td>'.$image['nom_fichier'].'</td>';
		$table .= '<td>'.$date.'</td>';
		$table .= '</tr>';
	}
	$table .= '</table>';
	echo $table;

 ?>
<a href="insert.php">Ajouter une image</a>