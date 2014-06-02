<?php
require_once('../init.php');
require_once('header-admin.php');

//on récupère les paramètres GET
$query = $_SERVER['QUERY_STRING'];

	//si il y a un utilisateur connecté
	if(isConnected()){

		//initialistation et définition des variables de tri
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

		//affichage des termes de recherches sur la page
		if(isset($_POST['search'])){
			$search = htmlspecialchars($_POST['search']);
			echo '<p>Votre recherche : '.$search.'</p>';
		}
		else{
			$search = '';
		}

		//on crée la liste d'image par utilisateur
		$images = getListImgByUserId(['orderby' => $order, 'tri' => $trisql, 'search' => $search, 'id_user' => $_SESSION['id']]);

		//déclaration de la variable $table qui contiendra les infos
		$table = '<div><table>';

		//gestion de la suppression des images
		if(isset($_GET['imgid']) && !empty($_GET['imgid'])){
			$imgid = htmlspecialchars($_GET['imgid']);
			$infos_img = getInfosImg($imgid);
			$btn_confirm = '<a href="delete.php'.majParamGet($query, ['suppr'=>'ok']).'" class="oui"><i class="fa fa-check"></i> Oui</a>';
			$btn_annul = '<a href="index.php'.majParamGet($query, ['suppr'=>'annul']).'" class="non"><i class="fa fa-reply"></i> Non</a>';

			//on vérifie si l'image appartient bien à l'utilisateur connecté
			if($_SESSION['id'] == $infos_img['id_user']){
				$titre_img = $infos_img['titre'];
				
				//message de demande de confirmation
				if(isset($_GET['suppr']) && $_GET['suppr']=='confirm'){
					$table .= '<tr><td class="suppr" colspan=6>';
					//message en lui-même
					$table .= '<span>Confirmer la suppression de l\'image "'.$titre_img.'" ?</span>';
					//boutons confirmer et annuler
					$table .= '<div>'.$btn_confirm.$btn_annul.'</div>';
					$table .= '</td></tr>';
				}
			}
			else{
				$table .= '<tr><td class="suppr" colspan=6>';
				$table .= '<span>Cette image ne vous appartient pas, suppression interdite !</span>';
				$table .='</td></tr>';
			}	
		}

		//affichage du message au retour de la page delete.php
		if(isset($_GET['suppr']) && $_GET['suppr']=='ok'){
			$table .= '<tr><td class="confirm" colspan=6><i class="fa fa-check"></i> Fichier supprimé !</td></tr>';
		}
		//affichage du message au retour de la page update.php
		if(isset($_GET['modif'])){
			if($_GET['modif']=='ok'){
			$table .= '<tr><td class="confirm" colspan=6><i class="fa fa-check"></i> Fichier modifié !</td></tr>';
			}
			elseif($_GET['modif'] == 'noimage'){
				$table .= '<tr><td class="confirm" colspan=6><i class="fa fa-times"></i> Cette image n\'existe pas...</td></tr>';
			}
			elseif($_GET['modif'] == 'forbidden'){
				$table .= '<tr><td class="confirm" colspan=6><i class="fa fa-times"></i> Cette image ne vous appartient pas, modification interdite !</td></tr>';
			}
		}

		$table .= '<tr>';
		$table .= '<th></th>';
		$table .= '<th>Titre <a href="'.majParamGet($query, ['orderby'=>'image.titre', 'dir'=>$tri]).'"><i class="fa fa-sort"></i></a></th>';
		$table .= '<th>Description <a href="'.majParamGet($query, ['orderby'=>'image.description', 'dir'=>$tri]).'"><i class="fa fa-sort"></i></a></th>';
		$table .= '<th>Auteur <a href="'.majParamGet($query, ['orderby'=>'users.prenom', 'dir'=>$tri]).'"><i class="fa fa-sort"></i></a></th>';
		$table .= '<th>Date d\'ajout <a href="'.majParamGet($query, ['orderby'=>'image.date_ajout', 'dir'=>$tri]).'"><i class="fa fa-sort"></i></a></th>';
		$table .= '<th></th>';
		$nb_img = 0;

		//contenu du tableau >> les informations
		foreach ($images as $image){

			//déclaration des variables liées à chaque image
			$imgid = $image['id'];
			$nb_img++;
			//formatage de la date
			$date = new DateTime($image['date_ajout']);
			$date = $date->format('d/m/Y H:i:s');

			//on complète la table à chaque passage
			$table .= '<tr>';
			$table .= '<td><img class="miniature" src="../'.galerieImgDirectory().'/mini-'.$image['nom_fichier'].'" alt="image" ></td>';
			$table .= '<td>'.$image['titre'].'</td>';
			$table .= '<td>'.$image['description'].'</td>';
			$table .= '<td>'.$image['prenom'].'</td>';
			$table .= '<td>'.$date.'</td>';
			
			//bouton de suppression et modification		
			$table .= '<td><a href="index.php'.majParamGet($query, ['suppr'=>'confirm', 'imgid'=> $imgid, 'modif' => 'annul' ]).'"><i class="fa fa-times"></i></a> <a href="update.php'.majParamGet($query, ['imgid'=> $imgid, 'suppr' => 'annul', 'modif' => 'annul' ]).'"/><i class="fa fa-edit"></i></a></td>';
			$table .= '</tr>';
		}
		$table .= '</table></div>';

		//modification du titre <h1> de la page	en fonction du nopmbre d'images
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
		
		//affichage des infos sur la page
		echo $h1;
		echo $button;
		echo $table;
	
	//fin du if de $_SESSION
	}
	//si on n'est pas connecté : on redirige vers login.php
	else{
		header('Location: login.php');
	} 
require_once('footer-admin.php') ?>