<?php
	require_once('init.php');
//////gestion de la pagination
	//on vérifie l'existence de pageid
	if(isset($_GET['pageid']) && $_GET['pageid']!=null){
		$page_id = htmlspecialchars($_GET['pageid']);
	}
	else{
		$page_id = 1;
	}
//////gestion du titre de la page
	//on vérifie si le paramètre 'imgid' existe et n'est pas nul
	if(isset($_GET['imgid']) && $_GET['imgid']!=null){

		//on déclare (et on protège) l'id de l'image
		$imgid = $db->quote($_GET['imgid']);

		//on vérifie si l'image est bien dans la BDD
		$imgexist = isImgExist($imgid);
		if($imgexist==1){
			$titre_page = getTitre(htmlspecialchars($_GET['imgid'])). ' << ';
		}else{
			$titre_page = 'Pas d\'image << ';
		}
	}else{
		$titre_page = 'Pas d\'image << ';
	}
	include('header.php');
	$dir = galerieImgDirectory();

//////gestion de l'affichage de l'image
	//si le paramètre 'imgid' existe et est OK
	if(isset($imgexist)){

		//on récupère les infos de chaque image	
		$infos_image = getInfosImg($imgid);

		//si l'image est présente dans la BDD
		if($imgexist==1){
?>
		<h1><?= $infos_image['titre']?></h1>
		<nav>
			<a href="index.php?pageid=<?= $page_id?>">Retour à la galerie</a>
		</nav>
		<div class="image">
			<figure>
				<?php
					$imgurl = 'big-'.$infos_image['nom_fichier'];
					echo '<img src='.$dir.'/'.$imgurl.'>';?>
			
			<figcaption>
				<?= $infos_image['description'];?>
			</figcaption>
			</figure>

		<?php 
		}
		//si l'image N'est PAS présente dans la BDD
		else{?>
			<nav>
				<a href="index.php?pageid=<?= $page_id?>">Retour à la galerie</a>
			</nav>
			<div class="erreur">
				Pas d'image...!
			</div>
		<?php
		}
	}
	//si le paramètre 'imgid' N'existe PAS ou N'est PAS OK
	else{?>
		<nav>
			<a href="index.php?pageid=<?= $page_id?>">Retour à la galerie</a>
		</nav>
		<div class="erreur">
			Pas d'image...!
		</div>
		<?php } ?>
		</div>
<?php 
	include('footer.php');
?>