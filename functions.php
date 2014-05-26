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
	
	function getListImg($page_id = '', $orderby = 'date_ajout', $dir = 'DESC', $search = ''){
		global $db;
		global $img_par_page;

		//sécurisation de la variable $search
		$search = '%'.$search.'%';
		$search = $db->quote($search);

		//sécurisation des variables $orderby et $dir
		$champs_table = ['titre', 'auteur', 'date_ajout', 'description'];
		(in_array($orderby, $champs_table)) ? $orderby = $orderby : $orderby='';
		($dir == 'ASC' || $dir == 'DESC') ? $dir = $dir : $dir ='';
		
		//dans le cas où on limit le nombre d'images par page
		if(!empty($page_id) && !empty($img_par_page)){
			$page_id = ($page_id-1)*$img_par_page;
			$limit = 'LIMIT'.(int)$page_id.','.(int)$img_par_page;
		}
		else{
			$limit = '';
		}

		//on génère la liste d'images
		$list_img = $db->query("SELECT id, titre, auteur, nom_fichier, date_ajout, description
			FROM image
			WHERE titre LIKE $search
				OR auteur LIKE $search
				OR description LIKE $search
			ORDER BY $orderby $dir
			$limit");
		
		return $list_img;
	}

	function getListImgFront($page = 1) {
		return getListImg($page);
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
	function updateImage($id, $titre, $auteur, $description){
		global $db;
		$titre = $db->quote($titre);
		$auteur = $db->quote($auteur);
		$description = $db->quote($description);
		$update = $db->exec("UPDATE image SET titre = $titre, auteur = $auteur, description = $description WHERE id = $id");
		return $update;
	}
	function deleteImage($id){
		global $db;
		$id = (int)$id;
		$nom_fichier = $db->query("SELECT nom_fichier FROM image WHERE id=$id");
		$nom_fichier = $nom_fichier->fetch()['nom_fichier'];
		$nom_fichier = '../images/'.$nom_fichier;
		unlink($nom_fichier);
		$delete = $db->exec("DELETE FROM image WHERE id=$id");
		return $delete;
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

	//mise à jour ou ajout d'un paramètre GET en url
	function majParamGet($query, $params, $html_output = true){
		$parametres_retournes =[];

		if($query!=''){
			$parametres_recus = explode('&', $query);

			//on insère dans un tableau les valeurs reçues
			foreach($parametres_recus as $parametre_recu){
				$param_recu_valeur = substr($parametre_recu, strpos($parametre_recu, '=')+1);
				$param_recu_cle = substr($parametre_recu, 0, strpos($parametre_recu, '='));
				$parametres_retournes[$param_recu_cle]=$param_recu_valeur;
			}
			$parametres_recus_keys = array_keys($parametres_retournes);


			foreach($params as $key => $valeur){

				//si le paramètre est trouvé
				if(in_array($key, $parametres_recus_keys)){
					//on supprime la ligne du tableau
					unset($parametres_retournes[$key]);
				}
				//on complète le tableau
				$parametres_retournes[$key] = $valeur;

				//on renvoie les donner dans un tableau
				//déclaration d'un tableau vide
				$query = array();
				foreach ($parametres_retournes as $cle => $param) {

					//à chaque passage on ajoute une ligne dans le tableau
					$query[] = $cle.'='.$param;
				}
				//on recolle le tableau en chaîne séparée par &amp;
				$query = '?'.implode(($html_output ? '&amp;' : '&'), $query);
			}
		}
		//si il n'y a pas de paramètre dans l'URL
		else{
			foreach($params as $key => $valeur){
				$parametres_retournes[$key] = $valeur;

				//on renvoie les donner dans un tableau
				//déclaration d'un tableau vide
				$query = array();
				foreach ($parametres_retournes as $cle => $param) {

					//à chaque passage on ajoute une ligne dans le tableau
					$query[] = $cle.'='.$param;
				}
				//on recolle le tableau en chaîne séparée par &amp;
				$query = '?'.implode(($html_output ? '&amp;' : '&'), $query);
			}
		}
		return $query;
	}
/*
	function majParamGet2($query, $params, $html_output = true) {
		$params_orig = explode('&', $query);
		$new_params = array();
		foreach($params_orig as $v) {
			// $v contient quelque chose comme « cle=valeur », $k contient un index numérique
			list($k, $v) = explode('=', $v);
			// $k contient maintenant « cle », $v contient maintenant « valeur »
			$new_params[$k] = $v;
		}
		// $new_params est un tableau associatif ressemblant à $_GET
		$new_params = array_merge($new_params, $params);
		$new_query = '';
		if(count($new_params)) {
			$new_query = '?';
			$new_query_array = array();
			foreach($new_params as $k => $v) {
				$new_query_array[] = $k.'='.urlencode($v);
			}
			$new_query .= implode(($html_output ? '&amp;' : '&'), $new_query_array);
		}
		return $new_query;
	}
*/
