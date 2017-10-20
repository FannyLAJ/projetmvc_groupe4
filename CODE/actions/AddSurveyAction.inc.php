<?php

require_once("model/Survey.inc.php");
require_once("model/Response.inc.php");
require_once("actions/Action.inc.php");

class AddSurveyAction extends Action {

	/**
	 * Traite les données envoyées par le formulaire d'ajout de sondage.
	 *
	 * Si l'utilisateur n'est pas connecté, un message lui demandant de se connecter est affiché.
	 *
	 * Sinon, la fonction ajoute le sondage à la base de données. Elle transforme
	 * les réponses et la question à l'aide de la fonction PHP 'htmlentities' pour éviter
	 * que du code exécutable ne soit inséré dans la base de données et affiché par la suite.
	 *
	 * Un des messages suivants doivent être affichés à l'utilisateur :
	 * - "La question est obligatoire.";
	 * - "Il faut saisir au moins 2 réponses.";
	 * - "Merci, nous avons ajouté votre sondage.".
	 *
	 * Le visiteur est finalement envoyé vers le formulaire d'ajout de sondage en cas d'erreur
	 * ou vers une vue affichant le message "Merci, nous avons ajouté votre sondage.".
	 * 
	 * @see Action::run()
	 */
	public function run() {
		/* TODO START */
		
		if ($this->getSessionLogin() === null){
			$this->setAddSurveyFormView("Veuillez vous connecter avant.");
		}
		else {
			$questionSurvey = htmlentities($_POST['questionSurvey']);
			

			$responseSurvey1 = htmlentities($_POST['responseSurvey1']);
			$responseSurvey2 = htmlentities($_POST['responseSurvey2']);
			$responseSurvey3 = htmlentities($_POST['responseSurvey3']);
			$responseSurvey4 = htmlentities($_POST['responseSurvey4']);
			$responseSurvey5 = htmlentities($_POST['responseSurvey5']);

			if ($questionSurvey === null){
				$this->setAddSurveyFormView("La question est obligatoire.");
			} else {

				if ($responseSurvey1 === null){
					$this->setAddSurveyFormView("Il faut saisir au moins 2 réponses");
				} else {
					if ($responseSurvey2 === null){
						$this->setAddSurveyFormView("Il faut saisir au moins 2 réponses");
					} else {
						$this->database->saveSurvey($questionSurvey);

						$bdd = new PDO('mysql:host=localhost;dbname=sondages;charset=utf8', 'root', '');

						$id_survey = $bdd->query('SELECT id_survey FROM surveys ORDER BY id_survey DESC LIMIT 0, 1');
						while ($goodId = $id_survey->fetch())
						{
						    $theId = $goodId['id_survey'];
						}
						  
						$id_survey->closeCursor();

						$req = $bdd->prepare("INSERT INTO responses(id_survey, title) VALUES(:idSurvey, :titleResponse)");
						$req->execute(array(
						'idSurvey' => $theId,
						'titleResponse' => $responseSurvey1
						));
						$req = $bdd->prepare("INSERT INTO responses(id_survey, title) VALUES(:idSurvey, :titleResponse)");
						$req->execute(array(
						'idSurvey' => $theId,
						'titleResponse' => $responseSurvey2
						));

						if ($responseSurvey3 != null) {
							$req = $bdd->prepare("INSERT INTO responses(id_survey, title) VALUES(:idSurvey, :titleResponse)");
							$req->execute(array(
							'idSurvey' => $theId,
							'titleResponse' => $responseSurvey3
							));
						}
						if ($responseSurvey4 != null) {
							$req = $bdd->prepare("INSERT INTO responses(id_survey, title) VALUES(:idSurvey, :titleResponse)");
							$req->execute(array(
							'idSurvey' => $theId,
							'titleResponse' => $responseSurvey4
							));
						}
						if ($responseSurvey5 != null) {
							$req = $bdd->prepare("INSERT INTO responses(id_survey, title) VALUES(:idSurvey, :titleResponse)");
							$req->execute(array(
							'idSurvey' => $theId,
							'titleResponse' => $responseSurvey5
							));
						}

						$this->setMessageView($message = "Merci, nous avons ajouté votre sondage.", $style="alert-success");
					}
				}
			}
		}
		
		/* TODO END */
	}

	private function setAddSurveyFormView($message) {
		$this->setView(getViewByName("AddSurveyForm"));
		$this->getView()->setMessage($message, "alert-error");
	}

}

?>
