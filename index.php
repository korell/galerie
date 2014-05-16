<?php
	$titre = "Ma galerie : accueil";
	require_once('header.php');
	$dir = 'images';
	$table_images = scandir($dir);
	$nb_images = count($table_images);
?>
<h1><?= $titre?> (<?= $nb_images?> images)</h1>
<div class="gallery">
<?php
	foreach($table_images as $imgurl){
		if($imgurl[0] !='.'){
		echo '<div class="image"><a href="single.php?img='.$imgurl.'"><img src="'.$dir.'/'.$imgurl.'"></a></div>';
		}
	}
?>
</div>
<?php
	require_once('footer.php');
?>