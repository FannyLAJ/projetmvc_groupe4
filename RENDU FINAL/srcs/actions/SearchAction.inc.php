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
        if ($_POST["keyword"] == "") {
            //On affiche le message d'erreur
            $this->setMessageView("Vous devez entrer un mot clé  avant de lancer la recherche.", "alert-error");
            //Sinon
        } else {
            //On affiche la vue SurveysView
            $db = new Database();
            $surveys = $db->loadSurveysByKeyword($_POST["keyword"]);
            $view = getViewByName("Surveys");
            $view->setSurveys($surveys);
            $this->setView($view);
        }

    }

}

?>