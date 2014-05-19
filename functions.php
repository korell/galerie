<?php 
	function isImg($imgurl, $dir){
		if(file_exists($dir.'/'.$imgurl) && stripos($imgurl, '/') == FALSE){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}

	function connection(){
		global $config;
		try {
	    	$db = new PDO($config['dsn'], $config['username'], $config['password']);
	    	$db->exec('SET CHARACTER SET UTF8');
		} catch (PDOException $e) {
			echo 'Problème de connexion à la base de données';
	    	die($e->getMessage());
		}
		return $db;
	}

	function classPage(){
		$url = basename($_SERVER['PHP_SELF']);
		if($url=='single.php'){
			$class = 'image-seule';
		}elseif($url=='index.php'){
			$class='home';
		}
		return $class;

	}
	function galerieTitre(){
		global $db;
		$titre = $db->query('SELECT content FROM infos_globales WHERE meta="galerie_title"');
		$titre = $titre->fetchColumn();
		return $titre;
	}
	function galerieImgDirectory(){
		global $db;
		$img_directory = $db->query('SELECT content FROM infos_globales WHERE meta="img_directory"');
		$img_directory = $img_directory->fetchColumn();
		return $img_directory;
	}
	function getListImg(){
		global $db;
		$list_img = $db->query('SELECT id, titre, auteur, nom_fichier, date_ajout, description FROM image');
		return $list_img;
	}
	function getInfosImg($imgid){
		global $db;
		$infos = $db->query('SELECT id, titre, auteur, nom_fichier, date_ajout, description FROM image WHERE id='.$imgid);
		$infos = $infos->fetch();
		return $infos;
	}
	function insertImage($url, $titre, $auteur, $description){
		global $db;
		$url = $db->quote($url);
		$titre = $db->quote($titre);
		$auteur = $db->quote($auteur);
		$description = $db->quote($description);
		$insert = $db->exec("INSERT INTO image VALUES(NULL,$titre,NOW(),$auteur,$description, $url)");
		return $insert;
	}
	function getTitre($id){
		global $db;
		$titre = $db->query("SELECT titre FROM image WHERE id=$id");
		$titre = $titre->fetch()['titre'];
		return $titre;
	}