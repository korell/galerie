<?php
	require_once('init.php');
	$titre_page = '';
	require_once('header.php');
	$dir = galerieImgDirectory();
	$retour = '';
	$query = $_SERVER['QUERY_STRING'];
?>
<?php

	$images_par_page = $img_par_page;
	$nb_images = $db->query('SELECT COUNT(id) FROM image');
	$nb_images = $nb_images->fetchColumn();
	$nb_pages = getNbPages($images_par_page, $nb_images);

	//gestion de la pagination
	if(isset($_GET['pageid']) && $_GET['pageid']!=null && $_GET['pageid'] > 0){
		if($_GET['pageid'] <= $nb_pages){
			$page_id = htmlspecialchars((int)$_GET['pageid']);
		}
		elseif($_GET['pageid'] > $nb_pages){
			$page_id = $nb_pages;
		}
	}
	else{
		$page_id = 1;
	}
	if(isset($_GET['id_user']) && !empty($_GET['id_user'])){
		$id_user = $_GET['id_user'];
		$prenom = getUserInfosById($id_user)['prenom'];
		$users_list = getUsersList();
		$users_list_id =[];
		foreach($users_list as $user){
			$users_list_id [] = $user['id'];
		}
		if(in_array($id_user, $users_list_id)){
			$retour = '<a href="'.majParamGet($query, ['id_user' => '']).'"><i class="fa fa-angle-double-left"></i> Retour à toutes les photos</a>';
			$message = 'Les photos de '.$prenom;
			$args =['id_user' => $id_user];
			$list_img = getListImgByUserId($args);
		}
		else{
			$message = 'Pas d\'utilisateur connu';
			$list_img = getListImgFront($page_id);
		}
	}
	else{
		$message = '';
		$list_img = getListImgFront($page_id);
	}
?>
<h1><?= galerieTitre() ?></h1>
<div class='wrapper'>
	<h2><?=$message?></h2>
	<div class="galerie">
<?php		
	foreach ($list_img as $ligne){

		//on affiche les miniatures
		$imgurl = 'mini-'.$ligne['nom_fichier'];
		$imgid = $ligne['id'];
		$auteur = getInfosImg($imgid)['prenom'];
		$id_user = getInfosImg($imgid)['id_user'];

		$img_div = '<div class="image">';
		$img_div .= '<span class="auteur"><a href="index.php'.majParamGet($query, ['id_user' => $id_user]).'">'.$auteur.'</a></span>';
		$img_div .= '<a href="single.php'.majParamGet($query, ['imgid' => $imgid]).'">';
		$img_div .= '<img src="'.$dir.'/'.$imgurl.'" alt="'.$ligne['titre'].'"/>';
		$img_div .= '</a></div>';
		echo $img_div;
	}
?>
<div class="retour"><?=$retour?></div>
</div>

<!--Gestion de la navigation entre les pages-->
<nav>
	<ul>
		<?php if($page_id!=1){?><li class="first"><a href="index.php?pageid=<?php echo $page_id-1;?>">Page précédente</a></li>
		<?php }else{
			echo '<li class="first"></li>';
			}?>
		<?php
		$prev = '';
		$next = '';
		$nav = '<li><ul>';
				//les 4 cas de navigation et de la liste des numéros de pages
		if($nb_pages<=7){
			$i=1;
			$max = $nb_pages;
		}
		elseif($page_id > 4 && $page_id < $nb_pages-3){
			$i=$page_id-3;
			$max = $page_id+3;
			$next = '<li class="next"></li><li class="next"></li><li class="next"></li>';
			$prev = '<li class="prev"></li><li class="prev"></li><li class="prev"></li>';
		}
		elseif($page_id > 4 && $page_id >= $nb_pages-3){
			$i=$nb_pages-6;
			$max=$nb_pages;
			$prev = '<li class="prev"></li><li class="prev"></li><li class="prev"></li>';
		}
		elseif($page_id <= 4){
			$i=1;
			$max = 7;
			$next = '<li class="next"></li><li class="next"></li><li class="next"></li>';
		}
		$nav .= $prev;
		for ($i; $i <= $max; $i++) {
			if($i==$page_id){
			$nav .= '<li class="selected"><a href="index.php?pageid='.$i.'">'.$i.'</a></li>';
			}
			else{
			$nav .= '<li><a href="index.php?pageid='.$i.'">'.$i.'</a></li>';
			}
		}
		$nav.= $next;
		$nav .= '</ul></li>';
		echo $nav;
		?>
		<?php if($nb_pages!=$page_id){?><li class="last"><a href="index.php?pageid=<?php echo $page_id+1;?>">Page suivante</a></li>
		<?php }else{
			echo '<li class="last"></li>';
			}?>
	</ul>
</nav>
</div>
<?php
	require_once('footer.php');
?>