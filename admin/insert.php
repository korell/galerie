<?php 
/*
définir les valeurs par défaut (vides par défaut)
si formulaire renvoyé
	vérification des champs
	si erreurs
		on réécrit les valeurs par défaut
	sinon
		on insert le tout
		on créer une variable pour empêcher l'affichage du formulaire
si $formulaire_envoyé n'existe pas
	on affiche le formulaire
*/
?>
<?php 
	include('../init.php');
	include('header-admin.php');
?>
<?php
$url = $titre = $auteur = $description = $errors_list = '';
$error_msg_table = [];
$errors = 0;
$dir = galerieImgDirectory();
$champs = [
	'titre',
	'auteur',
	'description',
	];
$extensions_autorisees = ['jpg','png'];
if(!empty($_POST)){
	//vérification des champs text et textarea obligatoires
	foreach ($champs as $champ) {
		if(isset($_POST[$champ]) && !empty($_POST[$champ])){
			$$champ = htmlspecialchars($_POST[$champ]);
		}else{
			$errors++;
		}
	}
	//si l'un des champs est invalide
	if($errors > 0){
		if($errors == 1){
			$error_msg_table[] = '1 champ obligatoire n\'est pas correctement rempli';
		}
		else{
			$error_msg_table[] = $errors.' champs obligatoires ne sont pas correctement remplis';
		}
	}
	//si un fichier a été sélectionné
	if(!empty($_FILES['image']['name'])){
		//on récupère l'extension du fichier
			$extension = pathinfo(htmlspecialchars($_FILES['image']['name']), PATHINFO_EXTENSION);
			$extension = strtolower($extension);
			//si l'extension est dans la liste des extensions autorisées
			if(in_array(strtolower($extension), $extensions_autorisees)){
				//si l'image est inférieure au poids autorisé
				if(($_FILES['image']['error']!=2 && $_FILES['image']['error']!=1)){
					$heure = date('YmdHis');
					$nom_fichier_big = 'big-'.$heure;
					$url_fichier_big = '../'.$dir.'/'.$nom_fichier_big.'.'.$extension;
					if(!move_uploaded_file($_FILES['image']['tmp_name'], $url_fichier_big)){
						$error_msg_table[] = 'Problème de transfert de fichier';
						$errors++;
					}
		$url = $heure.'.'.$extension;

		//on génère la miniature
		$maxsize = 150;
		$carre = true; //true pour une miniature carrée
		genereMini($url_fichier_big, $maxsize, $carre, 'mini');

		genereMini($url_fichier_big, 500, false, 'moyen');

				}
				else{
					$error_msg_table[] = 'L\'image est trop lourde';
					$errors++;
				}
			}
			else{
				$error_msg_table[] = 'Mauvaise extension de fichier';
				$errors++;
			}
	}else{
		$error_msg_table[] = 'Aucun fichier n\'a été sélectionné';
		$errors++;
	}
	if($errors==0){
		insertImage($url, $titre, $auteur, $description);
		$ok='';
	}
}
?>
<?php if(isset($ok)){?>
	<h1>Image bien envoyée</h1>
	<img src="<?= $url_fichier_big?>" alt="<?= $titre?>">
	<p><?= $titre?></p>
	<p><?= $description?></p>
	<p><?= $auteur?></p>
	<p><a href="#">Modifier l'image</a><p>
	<p><a href="insert.php">Envoyer une autre image</a><p>
	<p><a href="../">Voir la galerie</a><p>
	<?php }
	else{
?>
<form method="post" enctype="multipart/form-data" class="image">
	<div class="inputs">
		<h1>Ajouter votre photo</h1>
		<p>
			<label for="titre">Titre photo</label>
			<input placeholder="Le titre de votre photo ici" value="<?=$titre?>" type="text" name="titre" id="titre" autofocus>
		</p>
		<p>
			<label for="description">Description</label>
			<textarea placeholder="La description de votre photo ici" name="description" id="description"><?=$description?></textarea>
		</p>
		<p>
			<label for="auteur">Auteur</label>
			<input placeholder="L'auteur de votre photo ici" value="<?=$auteur?>" type="text" name="auteur" id="auteur">
		</p>
		<p>
			<label for="image">Votre photo (formats autorisés : '.jpg', '.png')</label>
			<input type="hidden" name="MAX_FILE_SIZE" value="2000000">
			<input type="file" name="image" id="image">
		</p>
		<p>
			<button type="submit" name="submit" value="Ajouter"><i class="fa fa-check"></i> Ajouter</button>
		</p>
	</div>
	<?php 
	if($errors > 0){
		foreach ($error_msg_table as $error_msg) {
			$errors_list .= '<li>'.$error_msg.'</li>';
		}
		if($errors == 1){
		echo '<div class="erreurs"><p>1 petite erreur :</p><ul>';
		}
		else{
			echo '<div class="erreurs"><p>'.$errors.' petites erreurs :</p><ul>';
		}
		echo $errors_list;
		echo '</ul></div>';
	}?>
</form>
<?php } ?>
<?php include('footer-admin.php') ?>