<?php 
	include('../init.php');
	include('header-admin.php');

	
	
	$email = $psswd = $nom = '';
	$errors = 0;
	$error_msg_table = [];
	$errors_list = '';
	if(!empty($_POST)){
		//vérification du champ email
		if(isset($_POST['email']) && !empty($_POST['email'])){
			if(filter_var(htmlspecialchars($_POST['email']), FILTER_VALIDATE_EMAIL)){
				$email = htmlspecialchars($_POST['email']);
				$user_infos = getUserInfosByEmail($email);
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
		if(isset($_POST['psswd']) && !empty($_POST['psswd'])){
			if($email!=''){
				$hash = $user_infos['psswd'];
				$psswd = $_POST['psswd'];
				if(password_verify($psswd, $hash)) {
					$_SESSION['admin'] = true;
					$_SESSION['id'] = $user_infos['id'];
					header('Location: index.php');
				}
				else{
					$error_msg_table[] = 'L\'e-mail ou/et le mot de passe n\'est pas correct';
					$errors++;
				}
			}
		}else{
			$error_msg_table[] = 'Saisissez un mot de passe';
			$errors++;
		}	
	}			
?>
		<h1>Connexion</h1>
		<form method="post" class="image">
			<div class="inputs">
			<p>
				<label for="email">E-mail</label>
				<input placeholder="Votre e-mail" value="<?=$email?>" type="text" name="email" id="email" autofocus>
			</p>
			<p>
				<label for="psswd">Mot de passe</label>
				<input placeholder="Votre mot de passe" value="<?=$psswd?>" type="password" name="psswd" id="psswd">
			</p>
			<p>
				<button type="submit" name="submit"><i class="fa fa-check"></i> Se connecter</button>
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