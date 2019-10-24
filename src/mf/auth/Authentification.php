<?php


namespace mf\auth;


use mysql_xdevapi\Exception;

class Authentification extends AbstractAuthentification
{
    /*
         * Le constructeur :
         *
         * Faire le lien entre la variable de session et les attributs de la classe
         *
         *   La variables de session sont les suivante :
         *    - $_SESSION['user_login']
         *    - $_SESSION['access_level']
         *
         *  Algorithme :
         *
         *  Si la variable de session 'user_login' existe
         *
         *     - renseigner l'attribut $this->user_login avec sa valeur
         *     - renseigner l'attribut $this->access_level avec la valeur de
         *       la variable de session 'access_level'
         *     - mettre l'attribut $this->logged_in a vrai
         *
         *  sinon
         *     - mettre les valeurs : null, ACCESS_LEVEL_NONE et false
         *       respectivement dans les trois attributs.
         *
         */


    /**
     * Authentification constructor.
     */
    public function __construct()
    {
        if(array_key_exists('user_login', $_SESSION)){
        $this->user = $_SESSION['user_login'];
        $this->access_level = $_SESSION['access_level'];
        $this->logged_in = true;
        }
    }

    /*
     * La méthode updateSession :
     *
     * Méthode pour enregistrer la connexion d'un utilisateur dans la session
     *
     * ATTENTION : cette méthode est appelée uniquement quand la connexion
     *             réussie par la méthode login (cf. plus bas)
     *
     * @param String : $username, le login de l'utilisateur
     * @param String : $level, le niveau d'accès
     *
     *  Algorithme:
     *    - renseigner l'attribut $this->user_login avec le paramètre $username
     *    - renseigner l'attribut $this->access_level avec $level
     *
     *    - renseigner $_SESSION['user_login']  $username
     *    - renseigner $_SESSION['access_level'] $level

     *    - mettre l'attribut $this->logged_in à vrai
     *
     */

    protected function updateSession($username, $level)
    {
        $this->user_login = $username ;
        $this->access_level = $level ;

        $_SESSION['user_login'] =  $username;
        $_SESSION['access_level'] = $level;
    }

    /*
 * la méthode logout :
 *
 * Méthode pour effectuer la déconnexion :
 *
 * Algorithme :
 *
 *  - Effacer les variables $_SESSION['user_login'] et
 *    $_SESSION['access_right']
 *  - Réinitialiser les attributs $this->user_login, $this->access_level
 *  - Mettre l'attribut $this->logged_in a faux
 *
 */

    public function logout()
    {
        $_SESSION['user_login'] = "";
        $_SESSION['access_right'] = "";
        $this->user_login = "";
        $this->access_level = "";
        $this->logged_in = false;
    }

    /*
 * La méthode checkAccessRight:
 *
 * Méthode pour verifier le niveau d'accès de l'utilisateur.
 *
 * @param  int  : $requested, le niveau requis
 * @return bool : vrai si le niveaux requis est inférieur ou égale à la
 *                valeur du niveau de l'utilisateur
 *
 * Algorithme :
 *
 * Si $requested > $this->access_level
 *     retourner faux
 * Sinon
 *     retourner vrai
 */

    public function checkAccessRight($requested)
    {
        return $requested > $this->access_level ? false : true ;
    }

    /*
 * La méthode login:
 *
 * Méthode qui réalise la connexion d'un utilisateur.
 *
 * @param string : $username, l'identifiant fourni par l'utilisateur
 * @param string : $db_pass, le haché du mot de passe stocké en BD
 * @param string : $pass, le mot de passe fourni par l'utilisateur
 * @param integer: $level, le niveau d'accès de lutilisateur stocké en BD
 *
 * Algorithme :
 *
 *   Si le mot de passe ne corespond pas au haché
 *       Soulever une exception
 *   sinon
 *       Mettre a jour les variables de session (update_session)
 *
 */

    public function login($username, $db_pass, $given_pass, $level)
    {
        if(password_verify($given_pass, $db_pass)){

        }
        else
            throw new \Exception("Erreur d'authentification");
    }

    protected function hashPassword($password)
    {
        // TODO: Implement hashPassword() method.
    }

    protected function verifyPassword($password, $hash)
    {
        // TODO: Implement verifyPassword() method.
    }
}