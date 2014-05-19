<?php
	include('header.php');
	$dir = galerieImgDirectory();
?>
<h1><?= galerieTitre() ?></h1>
<div class="gallery">
<?php
	$list_img = getListImg();//récupère l'ensemble des images de la galerie
	$nb_images = $db->query('SELECT COUNT(id) FROM image');
	$nb_images = $nb_images->fetchColumn();
	foreach ($list_img as $ligne){
		$imgurl = $ligne['nom_fichier'];
		$imgid = $ligne['id'];

		$img_div = '<div class="image">';
		$img_div .= '<a href="single.php?imgid='.$imgid.'">';
		$img_div .= '<img src="'.$dir.'/'.$imgurl.'" alt="'.$ligne['titre'].'"/>';
		$img_div .= '</a></div>';
		echo $img_div;
	}
?>
</div>
<div class="subfooter">
	<span>La galerie contient actuellement <?= $nb_images?> images</span>
</div>
<?php
	require_once('footer.php');
?>