</div><!--fin du div wrapper-->
<footer>
	<?php if(isset($nb_images)){ ?>
	<div class="subfooter">
	<span>La galerie contient actuellement <?= $nb_images?> images sur <?= $nb_pages?> pages</span>
	</div>
	<?php }?>
</footer>
</body>
</html>