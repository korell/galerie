<?php
	$dir = 'images';
	include('functions.php');
	
	if(IsImg($_GET['img'], $dir)){
		$imgurl = $_GET['img'];
		$titre = str_replace('.jpg', '', $imgurl);
	}else{
		$titre = 'La photo n\'existe pas...!';
	}
	include('header.php');

?>
<h1><?= $titre?></h1>
<div class="single">
	<div>
		<a href="index.php">Retour à la galerie</a>
	</div>
	<div>
		<?php
			if(IsImg($_GET['img'], $dir)){
				$imgurl = htmlspecialchars($_GET['img']);
				echo '<img src='.$dir.'/'.$imgurl.'>';
			}
		?>
	</div>
</div>