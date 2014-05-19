<?php 
	include('../init.php');
	include('header.php');
?>
<form method="post" enctype="multipart/form-data">
	<p>
		<label for="titre">Titre photo</label>
		<input type="text" name="titre">
	</p>
	<p>
		<label for="description">Description</label>
		<textarea name="description"></textarea>
	</p>
	<p>
		<label for="auteur">Auteur</label>
		<input type="text" name="auteur">
	</p>
	<p>
		<label for="image">Votre photo</label>
		<input type="hidden" name="MAX_FILE_SIZE" value="2000000">
		<input type="file" name="image" id="image">
	</p>
	<p>
		<input type="submit" name="submit" value="Ajouter">
	</p>
</form>
<?php
if(!empty($_POST)){
	$dir = galerieImgDirectory();
	$heure = date('YmdHis');
	$nom_fichier = 'original'.$heure;
	echo $nom_fichier;
	$extension_upload = strtolower(  substr(  strrchr($_FILES['image']['name'], '.')  ,1)  );
	$url_fichier = '../'.$dir.'/'.$nom_fichier.'.'.$extension_upload;
	if(move_uploaded_file($_FILES['image']['tmp_name'], $url_fichier)){
		echo 'Transfert réussi';
	}
	$url = $nom_fichier.'.'.$extension_upload;
	$titre = $_POST['titre'];
	$auteur = $_POST['auteur'];
	$description = $_POST['description'];
	insertImage($url, $titre, $auteur, $description);
}
?>