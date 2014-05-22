<?php
	include('../init.php');
	include('header-admin.php');

	
	$tri = 'DESC';
	$trisql = 'DESC';
	if(isset($_GET['dir'])){
		if($_GET['dir']=='DESC'){
			$tri = 'ASC';
			$trisql = 'DESC';
		}
		else{
			$tri = 'DESC';
			$trisql = 'ASC';
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
	$images = getListImg('', $order, $trisql);
	$table = '<div><table>';
	$table .= '<tr>';
	$table .= '<th></th>';
	$table .= '<th>Titre <a href="?orderby=titre&amp;dir='.$tri.'"><i class="fa fa-sort"></i></a></th>';
	$table .= '<th>Description <a href="?orderby=description&amp;dir='.$tri.'"><i class="fa fa-sort"></i></a></th>';
	$table .= '<th>Auteur <a href="?orderby=auteur&amp;dir='.$tri.'"><i class="fa fa-sort"></i></a></th>';
	$table .= '<th>Date d\'ajout <a href="?orderby=date_ajout&amp;dir='.$tri.'"><i class="fa fa-sort"></i></a></th>';
	$table .= '<th></th>';
	foreach ($images as $image){
		$date = new DateTime($image['date_ajout']);
		$date = $date->format('d/m/Y H:i:s');
		$table .= '<tr>';
		$table .= '<td><img class="miniature" src="../'.galerieImgDirectory().'/'.$image['nom_fichier'].'" alt="image" ></td>';
		$table .= '<td>'.$image['titre'].'</td>';
		$table .= '<td>'.$image['description'].'</td>';
		$table .= '<td>'.$image['auteur'].'</td>';
		$table .= '<td>'.$date.'</td>';
		$table .= '<td><a href="delete.php"/><i class="fa fa-times"></i></a> <a href="update.php"/><i class="fa fa-edit"></i></a></td>';
		$table .= '</tr>';
	}
	$table .= '</table></div>';
	echo $table;

 ?>
<?php include('footer-admin.php') ?>