<?php 
	require_once('../init.php');
	require_once('header-admin.php');
	if(isConnected()){
?>
<?php

$id_user = $_SESSION['id'];
$errors_list ='';
$error_msg_table = [];
$dir = galerieImgDirectory();

if(isset($_GET['imgid'])){
	$imgid = htmlspecialchars($_GET['imgid']);
	$infosimg = getInfosImg($imgid);
	if($id_user == $infosimg['id_user']){
		$titre = $infosimg['titre'];
		$description = $infosimg['description'];
		$url_fichier = '../'.$dir.'/big-'.$infosimg['nom_fichier'];
		$auteur = $infosimg['prenom'];
	}
	else{
		header('Location: index.php?modif=forbidden');
	}
}
else{
	header('Location: index.php?modif=noimage');
}

$errors = 0;
$champs = [
	'titre',
	'description',
	];
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
	if($errors==0){
		updateImage($imgid, $titre, $id_user, $description);
		$args = 'Location: index.php'.majParamGet($_SERVER['QUERY_STRING'], ['modif' => 'ok'], false);
		echo $args;
		header($args);
	}
}
?>
<form method="post" class="image">
	<div class="inputs">
		<h1>Modifier votre photo</h1>
		<p>
			<img src="<?= $url_fichier?>" alt="<?= $titre?>">
		</p>
		<p>
			<label for="titre">Titre photo</label>
			<input placeholder="Le titre de votre photo ici" value="<?=$titre?>" type="text" name="titre" id="titre" autofocus>
		</p>
		<p>
			<label for="description">Description</label>
			<textarea placeholder="La description de votre photo ici" name="description" id="description"><?=$description?></textarea>
		</p>
		<p>
			<button type="submit" name="submit" value="Ajouter"><i class="fa fa-check"></i> Modifier</button>
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
<?php
require_once('footer-admin.php');
}//fin du if de $_SESSION
else{
	header('Location: login.php');
} ?>