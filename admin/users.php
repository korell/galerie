<?php
	require_once('../init.php');
	require_once('header-admin.php');
	if(isAdmin()){
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
	if(isset($_POST['search'])){
		$search = htmlspecialchars($_POST['search']);
		echo '<p>Votre recherche : '.$search.'</p>';
	}
	else{
		$search = '';
	}
	$users_list = getUsersList();

	//on récupère les paramètres GET
	$query = $_SERVER['QUERY_STRING'];

	$table = '<div><table>';
	if(isset($_GET['userid'])){
		$userid = htmlspecialchars($_GET['userid']);
		$prenom = ' "'.getUserInfosById($userid)['prenom'].'" ';
	
		if(isset($_GET['suppr']) && $_GET['suppr']=='confirm'){
			$table .= '<tr><td class="suppr" colspan=6>';
			$table .= '<span>Confirmer la suppression de l\'image'.$prenom.'?</span><div><a href="delete.php'.majParamGet($query, ['suppr'=>'ok']).'" class="oui"><i class="fa fa-check"></i> Oui</a><a href="index.php'.majParamGet($query, ['suppr'=>'annul']).'" class="non"><i class="fa fa-reply"></i> Non</a></div>';
			$table .='</td></tr>';
		}
	}
	if(isset($_GET['suppr']) && $_GET['suppr']=='ok'){
			$table .= '<tr><td class="confirm" colspan=6><i class="fa fa-check"></i> Fichier supprimé !</td></tr>';
	}
	$table .= '<tr>';
	$table .= '<th></th>';
	$table .= '<th>Prénom <a href="'.majParamGet($query, ['orderby'=>'titre', 'dir'=>$tri]).'"><i class="fa fa-sort"></i></a></th>';
	$table .= '<th>E-mail <a href="'.majParamGet($query, ['orderby'=>'auteur', 'dir'=>$tri]).'"><i class="fa fa-sort"></i></a></th>';
	$table .= '<th>Statut <a href="'.majParamGet($query, ['orderby'=>'auteur', 'dir'=>$tri]).'"><i class="fa fa-sort"></i></a></th>';

	$table .= '<th></th>';
	foreach ($users_list as $user){
		$table .= '<tr>';
		$table .= '<td><img class="miniature" src="../'.galerieImgDirectory().'/gravatars/'.$user['gravatar'].'" alt="'.$user['prenom'].'" ></td>';
		$table .= '<td>'.$user['prenom'].'</td>';
		$table .= '<td>'.$user['email'].'</td>';
		$table .= '<td>'.$user['status'].'</td>';
		$userid = $user['id'];
		
		$table .= '<td><a href="users.php'.majParamGet($query, ['suppr'=>'confirm', 'userid'=> $userid ]).'"><i class="fa fa-times"></i></a> <a href="update.php'.majParamGet($query, ['userid'=> $userid ]).'"/><i class="fa fa-edit"></i></a></td>';
		$table .= '</tr>';
	}
	$table .= '</table></div>';
?>
<h1>La liste des utilisateurs</h1> 
<?php	echo $table;?>
	
<?php include('footer-admin.php') ?>
	<?php
	}//fin du if de $_SESSION
	else{
		header('Location: index.php');
	} ?>