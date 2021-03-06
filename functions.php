<?php
	function isInit(){
		global $config;
		return $config['init'];
	}
	function isConnected(){
		if(isset($_SESSION['admin']) && $_SESSION['admin'] == true){
			$connected = true;
		}
		else{
			$connected = false;
		}
		return $connected;
	}
	function isAdmin(){
		if(isset($_SESSION['admin']) && $_SESSION['admin'] == true){
			$userid = $_SESSION['id'];
			$userstatus = getUserInfosById($userid)['status'];
			if($userstatus == 'admin'){
				$admin = true;
			}
		}
		else{
			$admin = false;
		}
		return $admin;
	}
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
	function currentPage(){
		$current_page = substr($_SERVER['PHP_SELF'], strripos($_SERVER['PHP_SELF'], '/')+1);
		return $current_page;
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
	
	function getListImg($page_id = '', $orderby = 'image.date_ajout', $dir = 'DESC', $search = ''){
		global $db;
		global $img_par_page;
		$orderby = 'image.date_ajout';

		//sécurisation de la variable $search
		$search = '%'.$search.'%';
		$search = $db->quote($search);

		//sécurisation des variables $orderby et $dir
		$champs_table = ['image.titre', 'users.prenom', 'image.date_ajout', 'image.description'];
		(in_array($orderby, $champs_table)) ? $orderby = $orderby : $orderby='';
		($dir == 'ASC' || $dir == 'DESC') ? $dir = $dir : $dir ='';
		
		//dans le cas où on limit le nombre d'images par page
		if(!empty($page_id) && !empty($img_par_page)){
			$page_id = ($page_id-1)*$img_par_page;
			$limit = 'LIMIT '.(int)$page_id.','.(int)$img_par_page;
		}
		else{
			$limit = '';
		}

		//on génère la liste d'images
		$list_img = $db->query("SELECT image.id, image.titre, users.prenom, image.nom_fichier, image.date_ajout, image.description
			FROM image
			INNER JOIN users
			ON image.id_user = users.id
			WHERE image.titre LIKE $search
				OR users.prenom LIKE $search
				OR image.description LIKE $search
			ORDER BY $orderby $dir
			$limit");
		
		return $list_img;
	}
	/*
	*
	*
	*renvoie la liste des images de chaque utilisateurs
	*
	*les arguments sont passés par un tableau associatif
	*
	*/
	function getListImgByUserId(array $args){
		global $db;
		global $img_par_page;

		(isset($args['page_id']) ? $page_id = $args['page_id'] : $page_id = '');
		(isset($args['orderby']) ? $orderby = $args['orderby'] : $orderby = 'date');
		(isset($args['tri']) ? $dir = $args['tri'] : $dir = 'DESC');
		(isset($args['search']) ? $search = $args['search'] : $search = '');
		//argument obligatoire
		(isset($args['id_user']) ? $id_user = $args['id_user'] : $id_user = '');
	
		//sécurisation de la variable $search
		$search = '%'.$search.'%';
		$search = $db->quote($search);

		//sécurisation des variables $orderby et $dir
		$champs_table = ['titre', 'auteur', 'date', 'description'];
		(in_array($orderby, $champs_table)) ? $orderby = $orderby : $orderby='';
		($dir == 'ASC' || $dir == 'DESC') ? $dir = $dir : $dir ='';
		
		//dans le cas où on limit le nombre d'images par page
		if(!empty($page_id) && !empty($img_par_page)){
			$page_id = ($page_id-1)*$img_par_page;
			$limit = 'LIMIT '.(int)$page_id.','.(int)$img_par_page;
		}
		else{
			$limit = '';
		}
		$id_user = (int)$id_user;
		//on génère la liste d'images
		$list_img = $db->query("SELECT image.id, image.titre AS titre, users.prenom AS auteur, image.nom_fichier, image.date_ajout AS date, image.description AS description
			FROM image
			INNER JOIN users
			ON image.id_user = users.id
			WHERE image.id_user = $id_user
				AND (image.titre LIKE $search
				OR users.prenom LIKE $search
				OR image.description LIKE $search)
			ORDER BY $orderby $dir
			$limit");
		return $list_img;
	}
	function getIdImgUser($id_user){
		global $db;
		$id_user = (int)$id_user;
		$list_img_user = $db->query("SELECT image.id 
			FROM image 
			JOIN users
			ON image.id_user = users.id
			WHERE image.id_user = $id_user
 			");
		$list_img_user_table = [];
		foreach ($list_img_user as $img_id) {
			$list_img_user_table[] = $img_id['id'];
		}
		return $list_img_user_table;

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
		$infos = $db->query('SELECT image.id id_img, image.titre, image.nom_fichier, image.date_ajout, image.description, users.prenom , users.id id_user
			FROM image
			INNER JOIN users
			ON image.id_user = users.id
			WHERE image.id='.$imgid);
		$infos = $infos->fetch();
		return $infos;
	}
	function insertImage($url, $titre, $description, $id_user){
		global $db;
		$id_user = (int)$id_user;
		$url = $db->quote($url);
		$titre = $db->quote($titre);
		$description = $db->quote($description);
		$insert = $db->exec("INSERT INTO image VALUES(NULL,$titre,NOW(),$description, $url, $id_user)");
		return $insert;
	}
	function updateImage($id, $titre, $id_user, $description){
		global $db;
		$titre = $db->quote($titre);
		$id_user = (int)$id_user;
		$description = $db->quote($description);
		$update = $db->exec("UPDATE image SET titre = $titre, id_user = $id_user, description = $description WHERE id = $id");
		return $update;
	}
	function deleteImage($id){
		global $db;
		$id = (int)$id;
		$nom_fichier = $db->query("SELECT nom_fichier FROM image WHERE id=$id");
		$nom_fichier = $nom_fichier->fetch()['nom_fichier'];
		unlink('../images/big-'.$nom_fichier);
		unlink('../images/moyen-'.$nom_fichier);
		unlink('../images/mini-'.$nom_fichier);
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
	function genereMini($imgsrc, $maxwidth = 150, $carre = true, $taille = 'mini'){
		$img_directory = '../'.galerieImgDirectory();
		$img_name = str_replace($img_directory.'/big-', '', $imgsrc);
		$img_name = substr($img_name, 0, strripos($img_name, '.'));
		$imginfos = getimagesize($imgsrc);

		$extension = substr($imgsrc, strripos($imgsrc, '.'));

		$type_mime = $imginfos['mime'];
    	$type = str_replace('image/', '', $type_mime);

		$width_src = $imginfos[0];
		$height_src = $imginfos[1];

		$ratio = $width_src/$height_src;
		$decalageX = $decalageY = 0;

		if($height_src > $maxwidth && $width_src > $maxwidth){
			if($ratio >= 1){//paysage
				$newheight = $maxwidth;
				$newwidth = $maxwidth * $ratio;
				$decalageX = ($newwidth-$maxwidth)/-2;
			}
			else{//portrait
				
				$newwidth = $maxwidth;
				$newheight = $maxwidth / $ratio;
				$decalageY = ($newheight-$maxwidth)/-2;
			}
		}
		else{
			$newwidth = $width_src;
			$newheight = $height_src;
		}
		//ouverture de l'image originale
		$function = 'imagecreatefrom'.$type;
		$imgsrc = $function($imgsrc);

		//création de l'image carrée recevant les futures données
		if($carre == true){
			$newimage = imagecreatetruecolor($maxwidth, $maxwidth);
		}
		else{
			$decalageX = 0;
			$decalageY = 0;
			$newimage = imagecreatetruecolor($newwidth, $newheight);
		}

		//options à mettre en place plus tard
		//gestion des transparences pour les GIF et PNG

		//on redimensionne et on recadre
		imagecopyresampled($newimage, $imgsrc, $decalageX, $decalageY, 0, 0, $newwidth, $newheight, $width_src, $height_src);

		/*if(!file_exists($img_directory.'/minis')){
			echo 'Le dossier n\'existe pas';
			mkdir($img_directory.'/minis');
		}*/

		$imgdest = $img_directory.'/'.$taille.'-'.$img_name.$extension;
		imagejpeg($newimage, $imgdest, 90);
	}
	function getUsersList(){
		global $db;
		$users_list = $db->query("SELECT id, prenom, email, status, gravatar FROM users");
		return $users_list;
	}
	function getUserInfosByEmail($email){
		global $db;
		$email = $db->quote($email);
		$user_infos = $db->query("SELECT id, email, psswd, status FROM users WHERE email = $email");
		$user_infos = $user_infos->fetch();
		return $user_infos;
	}
	function getUserInfosById($id){
		global $db;
		$id=(int)$id;
		$user_infos = $db->query("SELECT id, email, prenom, psswd, status, gravatar FROM users WHERE id = $id");
		$user_infos = $user_infos->fetch();
		return $user_infos;
	}
	function createAccount($email, $psswd, $prenom, $status = 'membre', $gravatar = 'defaut.jpg'){
		global $db;
		(isInit() ? $status = 'admin' : $admin = 'membre');
		$email = $db->quote($email);
		$psswd = $db->quote(password_hash($psswd, PASSWORD_DEFAULT, array('cost' => 13)));
		$prenom = $db->quote($prenom);
		$status = $db->quote($status);
		$gravatar = $db->quote($gravatar);
		$create = $db->exec("INSERT INTO users VALUES (NULL, $email, $psswd, $prenom, $status, $gravatar)");
		return $create;
	}
	function updateAccount($id, $email, $psswd, $prenom, $gravatar = 'defaut.jpg'){
		global $db;
		$id = (int)$id;
		$email = $db->quote($email);
		$prenom = $db->quote($prenom);
		$gravatar = $db->quote($gravatar);
		if($psswd == ''){
			$update = $db->exec("UPDATE users SET gravatar = $gravatar, email = $email, prenom = $prenom WHERE id = $id");
		}
		else{
			$psswd = $db->quote(password_hash($psswd, PASSWORD_DEFAULT));
			$update = $db->exec("UPDATE users SET email = $email, psswd = $psswd, prenom = $prenom, gravatar = $gravatar WHERE id = $id");
		}

		return $update;
	}
	function emailExists($email){
		global $db;
		$email = $db->quote($email);
		$count = $db->query("SELECT COUNT(id) FROM users WHERE email=$email");
		$count = $count->fetchColumn();
		if($count > 0){
			return true;
		}
		else{
			return false;
		}  
	}