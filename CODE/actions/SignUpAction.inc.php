<?php

require_once("actions/Action.inc.php");

class SignUpAction extends Action {

	/**
	 * Traite les données envoyées par le formulaire d'inscription
	 * ($_POST['signUpLogin'], $_POST['signUpPassword'], $_POST['signUpPassword2']).
	 *
	 * Le compte est crée à l'aide de la méthode 'addUser' de la classe Database.
	 *
	 * Si la fonction 'addUser' retourne une erreur ou si le mot de passe et sa confirmation
	 * sont différents, on envoie l'utilisateur vers la vue 'SignUpForm' contenant 
	 * le message retourné par 'addUser' ou la chaîne "Le mot de passe et sa confirmation 
	 * sont différents.";
	 *
	 * Si l'inscription est validée, le visiteur est envoyé vers la vue 'MessageView' avec
	 * un message confirmant son inscription.
	 *
	 * @see Action::run()
	 */
	public function run() {

		//Si tous les champs du formulaire sont remplis, on traite les données.
		if (isset($_POST['signUpLogin']) && isset($_POST['signUpPassword']) && isset($_POST['signUpPassword2'])) {
			//On récupères les valeurs pour des facilités d'écriture.
			$nickname = $_POST['signUpLogin'];
			$password = $_POST['signUpPassword'];
			$password2 = $_POST['signUpPassword2'];

			//Si l'un des deux mots de passe est différent de l'autre, on fait appel à la méthode
			//setSignUpFormView pour afficher le message d'erreur.
			if ($password !== $password2) {
				$this->setSignUpFormView("Le mot de passe et sa confirmation sont différents.");
            		}

                        //Sinon, on transmet les données à la méthode addUser
			//après avoir instancié un nouvel objet Database.
			//Si la méthode renvoie une erreur, on l'affiche grâce à la méthode
			//setSignUpFormView.
			//Sinon, on stocke le login en session et on notifie l'utilisateur
			//que son inscription est réussie.
			else {
				$db = new Database();
                		$res = $db->addUser($nickname, $password);

                		if ($res !== true) {
                			$this->setSignUpFormView($res);
				}
				else {
					$this->setSessionLogin($nickname);
                    			$this->setView(getViewByName("Message"));
                    			$this->getView()->setMessage("Inscription réussie !", "alert-success");
                		}
			}
		}
    }

	private function setSignUpFormView($message) {
		$this->setView(getViewByName("SignUpForm"));
		$this->getView()->setMessage($message, "alert-error");
	}
}


?>
