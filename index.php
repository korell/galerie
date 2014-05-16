<?php
	$titre = "Ma galerie : accueil";
	include('header.php');
	$dir = 'images';
	$table_images = scandir($dir);
	$nb_images = count($table_images);
?>
<h1><?= $titre?> (<?= $nb_images?> images)</h1>
<div class="gallery">
<?php
	foreach($table_images as $imgurl){
		if($imgurl[0] !='.'){
		echo '<a href="single.php?img='.$imgurl.'"><img src="'.$dir.'/'.$imgurl.'"></a>';
		}
	}
?>
</div>
<?php
	include('footer.php');
?>