<?php
	include('init.php');
	$titre_page = '';
	include('header.php');
	$dir = galerieImgDirectory();
?>
<h1><?= galerieTitre() ?></h1>
<div class='wrapper'>
	<div class="galerie">
<?php
	//$list_img = getListImg();//récupère l'ensemble des images de la galerie

	//gestion de la pagination
	if(isset($_GET['pageid']) && $_GET['pageid']!=null && $_GET['pageid']>0){
		$page_id = htmlspecialchars((int)$_GET['pageid']);
	}
	else{
		$page_id = 1;
	}
	$images_par_page = (int)nbImagesParPages();
	$list_img = getListImg($page_id, /*$images_par_page*/2);
	
	$nb_images = $db->query('SELECT COUNT(id) FROM image');
	$nb_images = $nb_images->fetchColumn();
	$nb_pages = getNbPages(/*$images_par_page*/2, $nb_images);
	foreach ($list_img as $ligne){
		$imgurl = $ligne['nom_fichier'];
		$imgid = $ligne['id'];

		$img_div = '<div class="image">';
		$img_div .= '<a href="single.php?imgid='.$imgid.'&pageid='.$page_id.'">';
		$img_div .= '<img src="'.$dir.'/'.$imgurl.'" alt="'.$ligne['titre'].'"/>';
		$img_div .= '</a></div>';
		echo $img_div;
	}
?>
</div>

<!--Gestion de la navigation entre les pages-->
<nav>
	<ul>
		<?php if($page_id!=1){?><li class="first"><a href="index.php?pageid=<?php echo $page_id-1;?>">Page précédente</a></li>
		<?php }else{
			echo '<li class="first"></li>';
			}?>
		<?php
		$prev = '';
		$next = '';
		$nav = '<li><ul>';
				//les 4 cas de navigation et de la liste des numéros de pages
		if($nb_pages<=7){
			$i=1;
			$max = $nb_pages;
		}
		elseif($page_id > 4 && $page_id < $nb_pages-3){
			$i=$page_id-3;
			$max = $page_id+3;
			$next = '<li class="next"></li><li class="next"></li><li class="next"></li>';
			$prev = '<li class="prev"></li><li class="prev"></li><li class="prev"></li>';
		}
		elseif($page_id > 4 && $page_id >= $nb_pages-3){
			$i=$nb_pages-6;
			$max=$nb_pages;
			$prev = '<li class="prev"></li><li class="prev"></li><li class="prev"></li>';
		}
		elseif($page_id <= 4){
			$i=1;
			$max = 7;
			$next = '<li class="next"></li><li class="next"></li><li class="next"></li>';
		}
		$nav .= $prev;
		for ($i; $i <= $max; $i++) {
			if($i==$page_id){
			$nav .= '<li class="selected"><a href="index.php?pageid='.$i.'">'.$i.'</a></li>';
			}
			else{
			$nav .= '<li><a href="index.php?pageid='.$i.'">'.$i.'</a></li>';
			}
		}
		$nav.= $next;
		$nav .= '</ul></li>';
		echo $nav;
		?>
		<?php if($nb_pages!=$page_id){?><li class="last"><a href="index.php?pageid=<?php echo $page_id+1;?>">Page suivante</a></li>
		<?php }else{
			echo '<li class="last"></li>';
			}?>
	</ul>
</nav>
</div>
<?php
	require_once('footer.php');
?>