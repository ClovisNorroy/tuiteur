<?php


namespace mf\auth;


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

    protected function updateSession($username, $level)
    {
        // TODO: Implement updateSession() method.
    }

    public function logout()
    {
        // TODO: Implement logout() method.
    }

    public function checkAccessRight($requested)
    {
        // TODO: Implement checkAccessRight() method.
    }

    public function login($username, $db_pass, $given_pass, $level)
    {
        // TODO: Implement login() method.
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