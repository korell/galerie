<?php
	include('init.php');
	if(isset($_GET['imgid'])){
		$titre_page = getTitre($_GET['imgid']). ' << ';
	}else{
		$titre_page = 'Pas d\'image << ';
	}
	include('header.php');
	$dir = galerieImgDirectory();

	if(isset($_GET['imgid']) && $_GET['imgid']!=null){
		//on ajoute des quotes autour de la variable
		$imgid = $db->quote($_GET['imgid']);
		$imgexist = $db->query('SELECT COUNT(id) FROM image WHERE id='.$imgid);
		$imgexist = $imgexist->fetchColumn();
		//on récupère les infos de chaque image	
		$infos_image = getInfosImg($imgid);

		if($imgexist==1){
?>
		<h1><?= $infos_image['titre']?></h1>
		<nav>
			<a href="index.php">Retour à la galerie</a>
		</nav>
		<div class="single">
			<figure>
				<?php
					$imgurl = $infos_image['nom_fichier'];
					echo '<img src='.$dir.'/'.$imgurl.'>';?>
			
			<figcaption>
				<?= $infos_image['description'];?>
			</figcaption>
			</figure>

		<?php 
		}else{?>
			<nav>
				<a href="index.php">Retour à la galerie</a>
			</nav>
			<div class="erreur">
				Pas d'image...!
			</div>
		<?php
		}
	}else{?>
		<nav>
			<a href="index.php">Retour à la galerie</a>
		</nav>
		<div class="erreur">
			Pas d'image...!
		</div>
		<?php } ?>
		</div>
<?php 
	include('footer.php');
?>