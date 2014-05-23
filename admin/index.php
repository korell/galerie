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
	if(isset($_GET['imgid'])){
		$imgid = htmlspecialchars($_GET['imgid']);
		$imageseule = getInfosImg($imgid)['titre'];
	}
		

	$table = '<div><table>';
	if(isset($_GET['confirm']) && $_GET['confirm']=='ok'){
			$args = $_SERVER['QUERY_STRING'];
			$table .= '<tr><td class="suppr" colspan=6><span>Confirmer la suppression de l\'image "'.$imageseule.'" ?</span><div><a href="delete.php?'.$args.'" class="oui"><i class="fa fa-check"></i> Oui</a><a href="index.php" class="non"><i class="fa fa-reply"></i> Non</a></div></td></tr>';
		}
		elseif(isset($_GET['suppr']) && $_GET['suppr']=='ok'){
			$table .= '<tr><td class="confirm" colspan=6><i class="fa fa-check"></i> Fichier supprimé !</td></tr>';
		}
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
		$imgid = $image['id'];
		
		if($_SERVER['QUERY_STRING']!=''){
			$args = '?confirm=ok&amp;'.$_SERVER['QUERY_STRING'].'&amp;imgid='.$imgid;
		}
		else{
			$args = '?confirm=ok&amp;imgid='.$imgid;
		}
		//utilisation d'une fonction trouvée sur studiovitamine.com...
		$table .= '<td><a href="index.php'.$args.'"><i class="fa fa-times"></i></a> <a href="update.php"/><i class="fa fa-edit"></i></a></td>';
		$table .= '</tr>';
	}
	$table .= '</table></div>';
	echo $table;

?>
<?php include('footer-admin.php') ?>