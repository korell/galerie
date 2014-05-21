<?php
	include('../init.php');
	include('header-admin.php');
	$tri = 'DESC';
	if(isset($_GET['dir'])){
		if($_GET['dir']=='DESC'){
			$tri = 'ASC';
		}
	}
	else{
		$tri = 'ASC';
	}
	if(isset($_GET['orderby'])){
		$order = htmlspecialchars($_GET['orderby']);
	}
	else{
		$order = 'date_ajout';
	}
	$images = getListImg('', $order, $tri);
	$table = '<div class="admin"><table>';
	$table .= '<tr>';
	$table .= '<th></th><th>Titre <a href="?orderby=titre&amp;dir='.$tri.'"><img src="../css/img/fleches_tri.gif" alt=""></a></th><th>Description <a href="?orderby=description&amp;dir='.$tri.'"><img src="../css/img/fleches_tri.gif" alt=""></a></th><th>Auteur <a href="?orderby=auteur&amp;dir='.$tri.'"><img src="../css/img/fleches_tri.gif" alt=""></a></th><th>Date d\'ajout <a href="?orderby=date_ajout&amp;dir='.$tri.'"><img src="../css/img/fleches_tri.gif" alt=""></a></th>';
	foreach ($images as $image){
		$date = new DateTime($image['date_ajout']);
		$date = $date->format('d/m/Y H:i:s');
		$table .= '<tr>';
		$table .= '<td><img class="miniature" src="../'.galerieImgDirectory().'/'.$image['nom_fichier'].'" alt="image" ></td>';
		$table .= '<td>'.$image['titre'].'</td>';
		$table .= '<td>'.$image['description'].'</td>';
		$table .= '<td>'.$image['auteur'].'</td>';
		$table .= '<td>'.$date.'</td>';
		$table .= '</tr>';
	}
	$table .= '</table>';
	echo $table;

 ?>
<a href="insert.php">Ajouter une image</a>