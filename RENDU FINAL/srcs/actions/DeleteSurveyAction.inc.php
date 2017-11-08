<?php

require_once("actions/Action.inc.php");

class DeleteSurveyAction extends Action {

    /*
     * Récupère l'identifiant du sondage sélectionné par l'utilisateur dans la variable
     * $_POST['idSurvey'] et le supprime, ainsi que les réponses associées, grâce à
     * la méthode deleteSurvey de Database.
     *
     * Si une erreur quelconque se produit, un message d'erreur est affiché.
     *
     * Sinon, un message de confirmation est affiché.
     *
     */

    public function run() {
        $surveyId = "";

        if (isset($_POST["surveyId"])) $surveyId = (int)$_POST["surveyId"];

        $res = $this->database->deleteSurvey($surveyId);

        if ($res===false) {
            $this->setMessageView("Impossible de supprimer ce sondage.", "alert-error");
            return;
        }

        $this->setMessageView("Votre sondage a bien été supprimé !", "alert-success");

    }
}