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
		$dossier_parent = explode('/', $_SERVER['PHP_SELF']);
		$dossier_parent = $dossier_parent[count($dossier_parent)-2];

		$url = basename($_SERVER['PHP_SELF']);
		if($dossier_parent=='admin'){
			$class='admin';
		}
		elseif($url=='single.php'){
			$class = 'image-seule';
		}
		elseif($url=='index.php'){
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
	function nbImagesParPages(){
		global $db;
		$nb_img_par_page = $db->query('SELECT content FROM infos_globales WHERE meta="nb_img_par_page"');
		$nb_img_par_page = $nb_img_par_page->fetchColumn();
		return $nb_img_par_page;
	}
	function getListImg($page_id, $img_par_page){
		global $db;
		if($page_id>1){
			$page_id = ($page_id-1)*$img_par_page;
		}
		$list_img = $db->query("SELECT id, titre, auteur, nom_fichier, date_ajout, description FROM image LIMIT $page_id, $img_par_page");
		return $list_img;
	}
	function getNbPages($img_par_page, $nb_images){
		global $db;
		$nb_pages = $nb_images/$img_par_page;
		$nb_pages = ceil($nb_pages);
		return $nb_pages;
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
	//Vérifie si le paramètre 'id' de l'image passé en URL existe bien dans la BDD
	//retourne '1' si l'image est présente dans la BDD
	function isImgExist($imgid){
		global $db;
		$imgexist = $db->query('SELECT COUNT(id) FROM image WHERE id='.$imgid);
		$imgexist = $imgexist->fetchColumn();

		return $imgexist;
	}