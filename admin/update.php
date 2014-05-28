<?php 
	require_once('../init.php');
	require_once('header-admin.php');
	if(isConnected()){
?>
<?php
if(isset($_SESSION['prenom'])){
	$auteur = $_SESSION['prenom'];
}
else{
	$auteur = '';
}
$errors_list ='';
$error_msg_table = [];
$dir = galerieImgDirectory();

if(isset($_GET['imgid'])){
	$imgid = htmlspecialchars($_GET['imgid']);
	$infosimg = getInfosImg($imgid);
	$titre = $infosimg['titre'];
	$auteur = $infosimg['auteur'];
	$description = $infosimg['description'];
	$url_fichier = '../'.$dir.'/big-'.$infosimg['nom_fichier'];
}
else{
	$error_msg_table[] = 'Pas de photo à modifier';
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
		updateImage($imgid, $titre, $auteur, $description);
		$ok='';
	}
}
?>
<?php
	$list_img_id = [];
	$list_img = getListImg();
	foreach ($list_img as $img_id) {
		$list_img_id[] = $img_id['id'];
	}
	$list_img_user = getIdImgUser($_SESSION['id']);
?>
<?php if(isset($ok)){
	$query = $_SERVER['QUERY_STRING'];
	$params = ['imgid'=>$imgid];
	?>
	<h1>Image bien modifiée</h1>
	<img src="<?= $url_fichier?>" alt="<?= $titre?>">
	<p><?= $titre?></p>
	<p><?= $description?></p>
	<p><?= $auteur?></p>
	<p><a href="<?=majParamGet($query, $params)?>">Modifier l'image</a><p>
	<p><a href="insert.php">Envoyer une autre image</a><p>
	<p><a href="../">Voir la galerie</a><p>
	<?php }
	else{
?>
<form method="post" class="image">
	<div class="inputs">
<?php
	if(in_array($imgid, $list_img_user)){
?>		
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
<?php }
	elseif(in_array($imgid, $list_img_id)){
		echo '<h1>Vous n\'avez pas le droit de modifier cette image (petit malin...)</h1>
			<p>
				<img src="'.$url_fichier.'" alt="'.$titre.'">
			</p>';
	}
	else{
		echo '<h1>Cette image n\'existe pas...</h1>';
	}
?>			
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
<?php
}//fin du if de $_SESSION
else{
	header('Location: login.php');
} ?>