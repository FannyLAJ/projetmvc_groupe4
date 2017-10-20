<?php

require_once("actions/Action.inc.php");

class UpdateUserAction extends Action {

	/**
	 * Met à jour le mot de passe de l'utilisateur en procédant de la façon suivante :
	 *
	 * Si toutes les données du formulaire de modification de profil ont été postées
	 * ($_POST['updatePassword'] et $_POST['updatePassword2']), on vérifie que
	 * le mot de passe et la confirmation sont identiques.
	 * S'ils le sont, on modifie le compte avec les méthodes de la classe 'Database'.
	 *
	 * Si une erreur se produit, le formulaire de modification de mot de passe
	 * est affiché à nouveau avec un message d'erreur.
	 *
	 * Si aucune erreur n'est détectée, le message 'Modification enregistrée.'
	 * est affiché à l'utilisateur.
	 *
	 * @see Action::run()
	 */
	public function run() {

		if (isset($_POST['updatePassword']) && isset($_POST['updatePassword2'])) {

			$nickname = $_SESSION['login'];
			$UpdatePassword = $_POST['updatePassword'];
            $UpdatePassword2 = $_POST['updatePassword2'];

            if($UpdatePassword !== $UpdatePassword2 ) {
            	$this->setUpdateUserFormView("Le mot de passe est le confirmation du nouveau mot de passe ne sont pas identique");
			}
			else {
            	$res = $this->database->updateUser($nickname, $UpdatePassword);
                if ($res !== true) {

                    $this->setUpdateUserFormView($res);
                }

                else {

                    $this->setSessionLogin($nickname);

                    $this->setView(getViewByName("Message"));

                    $this->getView()->setMessage("Le changement de mot de passe est réussi");
                }
            }
			}
        return true;
        }


	private function setUpdateUserFormView($message) {
		$this->setView(getViewByName("UpdateUserForm"));
		$this->getView()->setMessage($message, "alert-error");
	}

}

?>
