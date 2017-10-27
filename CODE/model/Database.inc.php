<?php
require_once("model/Survey.inc.php");
require_once("model/Response.inc.php");

class Database {

    private $connection;

    /**
     * Ouvre la base de données. Si la base n'existe pas elle
     * est créée à l'aide de la méthode createDataBase().
     */

     public function __construct() {
         $dbHost = "localhost";
         $dbBd = "sondages";
         $dbPass = "";
         $dbLogin = "root";
         $url = 'mysql:host='.$dbHost.';dbname='.$dbBd;
         //$url = 'sqlite:database.sqlite';
         $this->connection = new PDO($url, $dbLogin, $dbPass);
         if ($this->connection != true) die("impossible d'ouvrir la base de données");
         $this->createDataBase();
     }

    /**
     * Initialise la base de données ouverte dans la variable $connection.
     * Cette méthode crée, si elles n'existent pas, les trois tables :
     * - une table users(nickname char(20), password char(50));
     * - une table surveys(id integer primary key autoincrement,
     *						owner char(20), question char(255));
     * - une table responses(id integer primary key autoincrement,
     *		id_survey integer,
     *		title char(255),
     *		count integer);
     */
    private function createDataBase() {
        $this->connection->exec('USE sondages;
                        CREATE TABLE IF NOT EXISTS users (
							nickname varchar(20) NOT NULL, 
							password varchar(50) NOT NULL,
							PRIMARY KEY (nickname)
						);
						CREATE TABLE IF NOT EXISTS surveys (
							id_survey int(4) NOT NULL AUTO_INCREMENT, 
							owner varchar(20) NOT NULL, 
							question varchar(255) NOT NULL, 
							PRIMARY KEY (id_survey)
						);
						CREATE TABLE IF NOT EXISTS responses (
							id_answers int(5) NOT NULL AUTO_INCREMENT, 
							id_survey int(4) NOT NULL,
							title varchar(255) NOT NULL, 
							count int(40), 
							PRIMARY KEY (id_answers)
						);
						ALTER TABLE responses ADD CONSTRAINT fk_id_survey FOREIGN KEY (id_survey) REFERENCES surveys(id_survey);
						ALTER TABLE surveys ADD CONSTRAINT fk_owner FOREIGN KEY (owner) REFERENCES users(nickname);');
    }

    /**
     * Vérifie si un pseudonyme est valide, c'est-à-dire,
     * s'il contient entre 3 et 10 caractères et uniquement des lettres.
     *
     * @param string $nickname Pseudonyme à vérifier.
     * @return boolean True si le pseudonyme est valide, false sinon.
     */
    private function checkNicknameValidity($nickname) {
        if (3>=strlen($nickname) || strlen($nickname)>=10) return false;
        else return true;
    }

    /**
     * Vérifie si un mot de passe est valide, c'est-à-dire,
     * s'il contient entre 3 et 10 caractères.
     *
     * @param string $password Mot de passe à vérifier.
     * @return boolean True si le mot de passe est valide, false sinon.
     */
    private function checkPasswordValidity($password) {
        if (3>=strlen($password) || strlen($password)>=10) return false;
        else return true;
    }

    /**
     * Vérifie la disponibilité d'un pseudonyme.
     *
     * @param string $nickname Pseudonyme à vérifier.
     * @return boolean True si le pseudonyme est disponible, false sinon.
     */
    private function checkNicknameAvailability($nickname) {
        //On récupère tous les champs 'nickname' de la table 'users'
        //Sous forme de tableau
        $res = $this->connection->query('SELECT nickname FROM users;');
        $nicknames = $res->fetch(PDO::FETCH_ASSOC);
        if ($nicknames != '') {
            if (in_array($nickname, $nicknames)) return false;
        }
        return true;
    }

    /**
     * Vérifie qu'un couple (pseudonyme, mot de passe) est correct.
     *
     * @param string $nickname Pseudonyme.
     * @param string $password Mot de passe.
     * @return boolean True si le couple est correct, false sinon.
     */
    public function checkPassword($nickname, $password) {
        $res = $this->connection->query('SELECT nickname, password FROM users WHERE nickname ="'.$nickname.'";');
        $login = $res->fetch(PDO::FETCH_ASSOC);
        if ($nickname == $login['nickname'] && $password == $login['password']) return true;
        else return false;
    }

    /**
     * Ajoute un nouveau compte utilisateur si le pseudonyme est valide et disponible et
     * si le mot de passe est valide. La méthode peut retourner un des messages d'erreur qui suivent :
     * - "Le pseudo doit contenir entre 3 et 10 lettres.";
     * - "Le mot de passe doit contenir entre 3 et 10 caractères.";
     * - "Le pseudo existe déjà.".
     *
     * @param string $nickname Pseudonyme.
     * @param string $password Mot de passe.
     * @return boolean|string True si le couple a été ajouté avec succès, un message d'erreur sinon.
     */
    public function addUser($nickname, $password) {

        if ($this->checkNicknameAvailability($nickname) == false){
            return "Le pseudo existe déjà.";

        }
        elseif ($this->checkNicknameValidity($nickname) == false) {
            return "Le pseudo doit contenir entre 3 et 10 caractères.";

        }
        elseif ($this->checkPasswordValidity($password) == false) {
            return "Le mot de passe doit contenir entre 3 et 10 caractères.";
        }
        else {
            $res = $this->connection->exec('INSERT INTO users (nickname, password) VALUES (
						"'.$nickname.'", "'.$password.'"
						);');
            if ($res) return true;
        }

    }

    /**
     * Change le mot de passe d'un utilisateur.
     * La fonction vérifie si le mot de passe est valide. S'il ne l'est pas,
     * la fonction retourne le texte 'Le mot de passe doit contenir entre 3 et 10 caractères.'.
     * Sinon, le mot de passe est modifié en base de données et la fonction retourne true.
     *
     * @param string $nickname Pseudonyme de l'utilisateur.
     * @param string $password Nouveau mot de passe.
     * @return boolean|string True si le mot de passe a été modifié, un message d'erreur sinon.
     */
    public function updateUser($nickname, $password) {
        if ($this->checkPasswordValidity($password) == false) {
            return "Le mot de passe doit contenir entre 3 et 10 caractères.";
        }
        else {
            $this->connection->exec('UPDATE users SET password = "'.$password.'" WHERE nickname = "'.$nickname.'"');
        }
        return true;
    }

    /**
     * Sauvegarde un sondage dans la base de donnée et met à jour les indentifiants
     * du sondage et des réponses.
     *
     * @param Survey $survey Sondage à sauvegarder.
     * @return boolean True si la sauvegarde a été réalisée avec succès, false sinon.
     */
    public function saveSurvey($survey) {
        $res=$this->connection->exec('INSERT INTO surveys (owner, question) VALUES
			("'.$survey->getOwner().'","'.$survey->getQuestion().'" );');
        if (!$res) return false;
        else {
            $res = $this->connection->query('SELECT id_survey FROM surveys WHERE owner="'.$survey->getOwner().'" AND question
            ="'.$survey->getQuestion().'";');
            $id_survey=$res->fetch(PDO::FETCH_ASSOC);
            $survey->setId($id_survey['id_survey']);
            return true;
        }
    }

    /**
     * Sauvegarde une réponse dans la base de donnée et met à jour son indentifiant.
     *
     * @param Response $response Réponse à sauvegarder.
     * @return boolean True si la sauvegarde a été réalisée avec succès, false sinon.
     */
    public function saveResponse($response) {
        $res = $this->connection->exec('INSERT INTO responses (id_survey, title, count) VALUES
 			('.$response->getIdSurvey().', "'.$response->getTitle().'", '.$response->getCount().');');
        if (!$res) return false;
        else {
            $res = $this->connection->query('SELECT id_answers FROM responses WHERE title="'.$response->getTitle().'";');
            $id_answer=$res->fetch(PDO::FETCH_ASSOC);
            $response->setId($id_answer['id_answers']);
        }
        return true;
    }

    /**
     * Charge l'ensemble des sondages créés par un utilisateur.
     *
     * @param string $owner Pseudonyme de l'utilisateur.
     * @return array(Survey)|boolean Sondages trouvés par la fonction ou false si une erreur s'est produite.
     */
    public function loadSurveysByOwner($owner) {
        $res = $this->connection->query('SELECT * FROM surveys WHERE owner="'.$owner.'";');
        if (!$res) return false;
        $survey = $res->fetch(PDO::FETCH_ASSOC);
        return $survey;
    }

    /**
     * Charge l'ensemble des sondages dont la question contient un mot clé.
     *
     * @param string $keyword Mot clé à chercher.
     * @return array(Survey)|boolean Sondages trouvés par la fonction ou false si une erreur s'est produite.
     */
    public function loadSurveysByKeyword($keyword) {
        $res = $this->connection->query('SELECT * FROM sondages WHERE question LIKE "%'.$keyword.'%";');
        if (!$res) return false;
        $survey = $res->fetch(PDO::FETCH_ASSOC);
        return $survey;
    }


    /**
     * Enregistre le vote d'un utilisateur pour la réponse d'identifiant $id.
     *
     * @param int $id Identifiant de la réponse.
     * @return boolean True si le vote a été enregistré, false sinon.
     */
    public function vote($id) {
        $res = $this->connection->exec('UPDATE TABLE answers SET count = count+1 WHERE id_answers ='.$id.';');
        if (!$res) return false;
        else return true;
    }

    /**
     * Construit un tableau de sondages à partir d'un tableau de ligne de la table 'surveys'.
     * Ce tableau a été obtenu à l'aide de la méthode fetchAll() de PDO.
     *
     * @param array $arraySurveys Tableau de lignes.
     * @return array(Survey)|boolean Le tableau de sondages ou false si une erreur s'est produite.
     */
    private function loadSurveys($arraySurveys) {
        $res = $this->connection->query('SELECT * FROM surveys;');
        if (!$res) return false;
        $surveys = $res->fetchAll();
        return $surveys;
    }

    /**
     * Construit un tableau de réponses à partir d'un tableau de ligne de la table 'responses'.
     * Ce tableau a été obtenu à l'aide de la méthode fetchAll() de PDO.
     *
     * @param Survey $survey Le sondage.
     * @param array $arraySurveys Tableau de lignes.
     * @return array(Response)|boolean Le tableau de réponses ou false si une erreur s'est produite.
     */
    private function loadResponses($survey, $arrayResponses) {
        $res = $this->connection->query('SELECT * FROM answers;');
        if (!$res) return false;
        $responses = $res->fetchAll();
        return $responses;
    }

}

?>
