<?php 
include('init.php');
?>
<!DOCTYPE html>
<html class="<?= CLASSPAGE?>">
<head>
    <title><?= galerieTitre()?> : accueil</title>
    <link rel="stylesheet" href="css/reset.css"/>
    <link rel="stylesheet" href="css/style.css"/>
    <link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
    <meta charset="utf-8" />
</head>
<body>
<?php 
	$db = connection();
?>