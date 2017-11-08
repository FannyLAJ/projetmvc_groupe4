<?php

require_once("actions/Action.inc.php");

class GetMySurveysAction extends Action {

	/**
	 * Construit la liste des sondages de l'utilisateur et le dirige vers la vue "ServeysView" 
	 * de façon à afficher les sondages.
	 *
	 * Si l'utilisateur n'est pas connecté, un message lui demandant de se connecter est affiché.
	 *
	 * @see Action::run()
	 */
	public function run() {
		$owner = $this->getSessionLogin();
		if ($owner === null) $this->setMessageView("Vous devez être authentifié pour effectuer cette action.");
		else {
            $db = new Database();
            $surveys = $db->loadSurveysByOwner($owner);
            $view = getViewByName("Surveys");
            $view->setSurveys($surveys);
            $this->setView($view);
		}
	}
}

?>
