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
					<li><button type="submit"><i class="fa fa-search"></i></button><input type="search" id="search" name="search" placeholder="Rechercher"></li>
					<li><a href="insert.php"><i class="fa fa-plus"></i> Ajouter une image</a></li>
					<li><a href="index.php"><i class="fa fa-home"></i> Accueil de l'admin</a></li>
					<li><a href="../"><i class="fa fa-eye"></i> Voir ma galerie</a></li>
					<li><a href="../"><i class="fa fa-power-off"></i> Déconnexion</a></li>
				</ul>
			</form>
		</nav>
	</header>
	<div class="wrapper">