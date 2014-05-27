<?php 
if(isset($_GET['s']) && $_GET['s'] == 'off'){
	$_SESSION = array();
	session_destroy();
	header('Location: login.php');
} ?>
<?php
if(isset($_SESSION['id'])){
	$id_user = $_SESSION['id'];
	$user_infos = getUserInfosById($id_user);
	$prenom = $user_infos['prenom'];
}
elseif(isset($_SESSION['email'])){
	$email = $_SESSION['email'];
	$user_infos = getUserInfosByEmail($email);
	$id_user = $user_infos['id'];
	$_SESSION['id'] = $id_user;
	$user_infos = getUserInfosById($id_user);
	$prenom = $user_infos['prenom'];
}


?>
<!DOCTYPE html>
<html class="<?= classPage()?>">
<head>
    <title>Admin << <?= galerieTitre()?></title>
    <link rel="stylesheet" href="../css/reset.css"/>
    <link rel="stylesheet" type="text/css" href="../css/font-awesome-4.1.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/style.css"/>
    <meta charset="utf-8" />
</head>
<body>
	<header>
		<nav>
			<form method="post">
				<ul>
					<?php
					if(isset($_SESSION['admin']) && $_SESSION['admin'] == true){?>
					<li><button type="submit"><i class="fa fa-search"></i></button><input type="search" id="search" name="search" placeholder="Rechercher"></li>
					<li><a href="insert.php"><i class="fa fa-plus"></i> Ajouter une image</a></li>
					<li><a href="index.php"><i class="fa fa-home"></i> Accueil de l'admin</a></li>
					<li><a href="../"><i class="fa fa-eye"></i> Voir ma galerie</a></li>
					<li><a href="account.php">Mon compte (<?=$prenom?>)</a></li>
					<li><a href="?s=off"><i class="fa fa-power-off"></i> Déconnexion</a></li>
					<?php }
					elseif(currentPage() == 'login.php'){?>
						<li><a href="signin.php"><i class="fa fa-power-off"></i> Créer un compte</a></li>
					<?php }
					else{?>
						<li><a href="login.php"><i class="fa fa-power-off"></i> Se connecter</a></li>
					<?php }?>
				</ul>

			</form>
		</nav>
	</header>
	<div class="wrapper">