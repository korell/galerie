<?php
	include('../init.php');
	include('header.php');
	$images = getListImg();
	$table = '<div class="admin"><table>';
	foreach ($images as $image){
		$table .= '<tr>';
		$table .= '<td><img src="../'.galerieImgDirectory().'/'.$image['nom_fichier'].'" alt="image" ></td>';
		$table .= '<td>'.$image['titre'].'</td>';
		$table .= '<td>'.$image['description'].'</td>';
		$table .= '<td>'.$image['auteur'].'</td>';
		$table .= '<td>'.$image['nom_fichier'].'</td>';
		$table .= '<td>'.$image['date_ajout'].'</td>';
		$table .= '</tr>';
	}
	$table .= '</table>';
	echo $table;

 ?>
<form method="post" enctype="multipart/form-data">
	<input type="hidden" name="MAX_FILE_SIZE" value="2000000">
	<label>Votre image : choisissez un fichier</label>
	<input type="file" name="image">
</form>