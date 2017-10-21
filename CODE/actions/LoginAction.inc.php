<?php

require_once("actions/Action.inc.php");

class LoginAction extends Action {

    /**
     * Traite les données envoyées par le visiteur via le formulaire de connexion
     * (variables $_POST['nickname'] et $_POST['password']).
     * Le mot de passe est vérifié en utilisant les méthodes de la classe Database.
     * Si le mot de passe n'est pas correct, on affiche le message "Pseudo ou mot de passe incorrect."
     * Si la vérification est réussie, le pseudo est affecté à la variable de session.
     *
     * @see Action::run()
     */

    public function run() {

        //Si les données du formulaire existent, on les traite
        if ($_POST['nickname'] !== '' && $_POST['password'] !== '') {
            $nickname = $_POST['nickname'];
            $password = $_POST['password'];

            //On instancie un objet Database et on fait appel à sa méthode checkPassword.
            //Si la méthode checkPassword retourne false, on affiche un message d'erreur.
            //Sinon, on stocke le login en session et on notifie l'utilisateur qu'il est connecté.
            $db = new Database();
            $res = $db->checkPassword($nickname, $password);
            if ($res != true) {
                $this->setMessageView("Pseudo ou mot de passe incorrect", "alert-error");
            }
            else {
                $this->setSessionLogin($nickname);
                $this->setView(getViewByName("Message"));
                $this->getView()->setMessage("Connexion réussie.", "alert-success");
            }
        }
        else {
            $this->setView(getViewByName("Message"));
            $this->getView()->setMessage("Pseudo ou mot de passe incorrect", "alert-error");
        }
    }
}

?>
