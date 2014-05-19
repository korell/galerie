<?php 
	include('../header.php');
 ?>
<form method="post" enctype="multipart/form-data">
	<input type="hidden" name="MAX_FILE_SIZE" value="2000000">
	<label>Votre image : choisissez un fichier</label>
	<input type="file" name="image">
</form>