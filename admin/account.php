<?php
	require_once('../init.php');
	require_once('header-admin.php');
	if(isConnected()){
	//$user_infos est déclaré dans le header-admin.php
	$email = $user_infos['email'];
	$prenom = $user_infos['prenom'];
	$psswd = '';
	$errors = 0;
	$error_msg_table = [];
	$errors_list = '';
	if(!empty($_POST)){

		if(isset($_POST['prenom']) && !empty($_POST['prenom'])){
			$prenom = htmlspecialchars($_POST['prenom']);
		}
		else{
			$error_msg_table[] = 'Saisissez votre prénom';
			$errors++;
		}
		//vérification du champ email
		if(isset($_POST['email']) && !empty($_POST['email'])){
			if(filter_var(htmlspecialchars($_POST['email']), FILTER_VALIDATE_EMAIL)){
				$email = htmlspecialchars($_POST['email']);
				
			}
			else{
				$error_msg_table[] = 'L\'email n\'est pas au bon format';
				$errors++;
			}
		}
		else{
			$error_msg_table[] = 'Saisissez votre e-mail';
			$errors++;
		}
		//vérification du champ password
		if(isset($_POST['psswd']) && !empty($_POST['psswd']) && isset($_POST['psswd-verif']) && !empty($_POST['psswd-verif'])){
			if($email!=''){
				if($_POST['psswd'] == $_POST['psswd-verif']){
					$psswd = $_POST['psswd'];
				}
				else{
					$error_msg_table[] = 'Les 2 mots de passe saisis ne sont pas identiques';
					$errors++;
				}
			}
		}else{
			$error_msg_table[] = 'Info : Mot de passe non modifié';
		}

		//on insère en base
		if($errors == 0){
			updateAccount($id_user, $email, $psswd, $prenom, $gravatar);
			header('Location: index.php');
		}	
	}			
?>
		<h1>Votre compte</h1>
		<form method="post" class="image">
			<div class="inputs">
			<p>
				<label for="prenom">Prénom</label>
				<input placeholder="Votre prénom" value="<?=$prenom?>" type="text" name="prenom" id="prenom" autofocus>
			</p>	
			<p>
				<label for="email">E-mail</label>
				<input placeholder="Votre e-mail" value="<?=$email?>" type="text" name="email" id="email" autofocus>
			</p>
			<p>
				<label for="psswd">Modifier votre mot de passe</label>
				<input placeholder="Votre nouveau mot de passe" value="" type="password" name="psswd" id="psswd">
			</p>
			<p>
				<label for="psswd-verif">Mot de passe - Vérification</label>
				<input placeholder="Saisissez de nouveau votre nouveau mot de passe" value="" type="password" name="psswd-verif" id="psswd-verif">
			</p>
			<p>
				<button type="submit" name="submit"><i class="fa fa-check"></i> Valider</button>
			</p>
			</div>
	<?php 
	if($errors > 0){
		foreach ($error_msg_table as $error_msg) {
			$errors_list .= '<li>'.$error_msg.'</li>';
		}
		if($errors == 1){
		echo '<div class="erreurs"><p>1 petite erreur :</p><ul>';
		}
		else{
			echo '<div class="erreurs"><p>'.$errors.' petites erreurs :</p><ul>';
		}
		echo $errors_list;
		echo '</ul></div>';
	}?>
	</form>
	<?php
	}//fin du if de $_SESSION
	else{
		header('Location: login.php');
	} ?>
