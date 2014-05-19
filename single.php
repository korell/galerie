<?php
	include('header.php');
	$dir = galerieImgDirectory();

	if(isset($_GET['imgid']) && $_GET['imgid']!=null){
		//on ajoute des quotes autour de la variable
		$imgid = $db->quote($_GET['imgid']);
		$imgexist = $db->query('SELECT COUNT(id) FROM image WHERE id='.$imgid);
		$imgexist = $imgexist->fetchColumn();

		if($imgexist==1){
		$infos_img = $db->query("SELECT titre, nom_fichier FROM image WHERE id=".$imgid);
		$infos_img = $infos_img->fetch();
		
?>
		<h1><?= $infos_img['titre']?></h1>
		<nav>
			<a href="index.php">Retour à la galerie</a>
		</nav>
		<div class="single">
			<figure>
				<?php
					$imgurl = $infos_img['nom_fichier'];
					echo '<img src='.$dir.'/'.$imgurl.'>';?>
			
			<figcaption>
				<?= getInfosImg($imgid)['description'];?>
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