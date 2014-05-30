<!DOCTYPE html>
<html class="<?= classPage()?>">
<head>
    <title><?= $titre_page?><?= galerieTitre()?></title>
    <link rel="stylesheet" href="css/reset.css"/>
    <link rel="stylesheet" href="css/style.css"/>
    <meta charset="utf-8" />
</head>
<body>
<?php
if(isInit()){
	$phase_init = '<div class="init">Attention ! Vous êtes en mode d\'initialisation. Pour en sortir, veuillez modifier la valeur dans le fichier \'config.php\'.</div>';
}
else{
	$phase_init = '';
}
echo $phase_init;