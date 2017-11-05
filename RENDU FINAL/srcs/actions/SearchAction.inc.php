<?php

require_once("actions/Action.inc.php");

class SearchAction extends Action {

	/**
	 * Construit la liste des sondages dont la question contient le mot clé
	 * contenu dans la variable $_POST["keyword"]. L'utilisateur est ensuite
	 * dirigé vers la vue "ServeysView" permettant d'afficher les sondages.
	 *
	 * Si la variable $_POST["keyword"] est "vide", le message "Vous devez entrer un mot clé
	 * avant de lancer la recherche." est affiché à l'utilisateur.
	 *
	 * @see Action::run()
	 */
	public function run() {
		//Si la recherche est vide
		if (empty($_POST["keyword"])) {
			//On affiche le message
			$this->setMessageView("Vous devez entrer un mot clé avant de lancer la recherche.", "alert-error");
		//Sinon
		} else {
			//On définie la BDD
			$db = new Database();
			//On set la vue Surveys
			$this->setView(getViewByName("Surveys"));
			//On récupere les sondages selon le mot clef
			$res = $db->loadSurveysByKeyword($_POST["keyword"]);
			//On ajour le sondage
			$this->getView()->setSurveys($res);
		}

	}

}

?>
