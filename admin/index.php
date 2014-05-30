<?php
	require_once('../init.php');
	require_once('header-admin.php');
	if(isConnected()){
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
		$order = 'image.date_ajout';
	}
	if(isset($_POST['search'])){
		$search = htmlspecialchars($_POST['search']);
		echo '<p>Votre recherche : '.$search.'</p>';
	}
	else{
		$search = '';
	}
	$images = getListImgByUserId('', $order, $trisql, $search, $_SESSION['id']);
	//on récupère les paramètres GET
	$query = $_SERVER['QUERY_STRING'];

	$table = '<div><table>';
	if(isset($_GET['imgid'])){
		$imgid = htmlspecialchars($_GET['imgid']);
		$imageseule = ' "'.getInfosImg($imgid)['titre'].'" ';
	
		if(isset($_GET['suppr']) && $_GET['suppr']=='confirm'){
			$table .= '<tr><td class="suppr" colspan=6>';
			$table .= '<span>Confirmer la suppression de l\'image'.$imageseule.'?</span><div><a href="delete.php'.majParamGet($query, ['suppr'=>'ok']).'" class="oui"><i class="fa fa-check"></i> Oui</a><a href="index.php'.majParamGet($query, ['suppr'=>'annul']).'" class="non"><i class="fa fa-reply"></i> Non</a></div>';
			$table .='</td></tr>';
		}
	}
	if(isset($_GET['suppr']) && $_GET['suppr']=='ok'){
			$table .= '<tr><td class="confirm" colspan=6><i class="fa fa-check"></i> Fichier supprimé !</td></tr>';
	}
	$table .= '<tr>';
	$table .= '<th></th>';
	$table .= '<th>Titre <a href="'.majParamGet($query, ['orderby'=>'image.titre', 'dir'=>$tri]).'"><i class="fa fa-sort"></i></a></th>';
	$table .= '<th>Description <a href="'.majParamGet($query, ['orderby'=>'image.description', 'dir'=>$tri]).'"><i class="fa fa-sort"></i></a></th>';
	$table .= '<th>Auteur <a href="'.majParamGet($query, ['orderby'=>'users.prenom', 'dir'=>$tri]).'"><i class="fa fa-sort"></i></a></th>';
	$table .= '<th>Date d\'ajout <a href="'.majParamGet($query, ['orderby'=>'image.date_ajout', 'dir'=>$tri]).'"><i class="fa fa-sort"></i></a></th>';
	$table .= '<th></th>';
	$nb_img = 0;
	foreach ($images as $image){
		$nb_img++;
		$date = new DateTime($image['date_ajout']);
		$date = $date->format('d/m/Y H:i:s');
		$table .= '<tr>';
		$table .= '<td><img class="miniature" src="../'.galerieImgDirectory().'/mini-'.$image['nom_fichier'].'" alt="image" ></td>';
		$table .= '<td>'.$image['titre'].'</td>';
		$table .= '<td>'.$image['description'].'</td>';
		$table .= '<td>'.$image['prenom'].'</td>';
		$table .= '<td>'.$date.'</td>';
		$imgid = $image['id'];
		
		$table .= '<td><a href="index.php'.majParamGet($query, ['suppr'=>'confirm', 'imgid'=> $imgid ]).'"><i class="fa fa-times"></i></a> <a href="update.php'.majParamGet($query, ['imgid'=> $imgid ]).'"/><i class="fa fa-edit"></i></a></td>';
		$table .= '</tr>';
	}
	$table .= '</table></div>';
?>
<?php 
if($nb_img==0){
	$h1 = '<h1>Vous n\'avez aucune image dans votre collection</h1>';
	$button = '<div class="pas-image"><button class="ajouter-image"><a href="insert.php"><i class="fa fa-plus"></i> Ajouter une image</a></button></div>';
	$table = '';
}
elseif($nb_img==1){
	$h1 = '<h1>Votre photo</h1>';
	$button = '';
}
else{
	$h1 = '<h1>La liste de vos '.$nb_img.' photos</h1>';
	$button = '';
}
?>
 
<?php
	echo $h1;
	echo $button;
	echo $table;
	}//fin du if de $_SESSION
	else{
		header('Location: login.php');
	} ?>
<?php include('footer-admin.php') ?>