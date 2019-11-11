<?php

namespace tweeterapp\view;

use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use mf\router\Router;
use tweeterapp\control\TweeterController;

class TweeterView extends \mf\view\AbstractView {
    private $router;
  
    /* Constructeur 
    *
    * Appelle le constructeur de la classe parent
    */
    public function __construct( $data ){
        parent::__construct($data);
        $this->router = new Router();
    }

    /* Méthode renderHeader
     *
     *  Retourne le fragment HTML de l'entête (unique pour toutes les vues)
     */ 
    private function renderHeader(){
        return '<div class = "theme-backcolor1"><h1>MiniTweeTR</h1></div>';
    }
    
    /* Méthode renderFooter
     *
     * Retourne le fragment HTML du bas de la page (unique pour toutes les vues)
     */
    private function renderFooter(){
        return 'La super app créée en Licence Pro &copy;2019';
    }

    /* Méthode renderHome
     *
     * Vue de la fonctionalité afficher tous les Tweets. 
     *  
     */
    
    private function renderHome(){
        $homeHTML="";
        $postLink = $this->router->urlFor('/post');
        foreach($this->data as $tweet){
            $text = $tweet['text'];
            $author = $tweet['authorNickName'];
            $tweetLink = $this->router->urlFor("/tweet", ['id'=> $tweet['id']]) ;
            $authorLink = $this->router->urlFor("/author", ['id' => $tweet['author']]) ;
            $homeHTML.= <<<EOT
            <div class = "tweet">
            <div class="tweet-text"><a href="$tweetLink">$text</a></div>
            <div class="tweet-author"> <a href="$authorLink">$author</a></div>
            </div>
            <hr>
EOT;
        }
        $homeHTML.=$this->renderPostTweet();
        return $homeHTML;
        /*
         * Retourne le fragment HTML qui affiche tous les Tweets. 
         *  
         * L'attribut $this->data contient un tableau d'objets tweet.
         * 
         */
        
        
    }
  
    /* Méthode renderUeserTweets
     *
     * Vue de la fonctionalité afficher tout les Tweets d'un utilisateur donné.
     * Retourne le fragment HTML pour afficher
     * tous les Tweets d'un utilisateur donné.
     *
     * L'attribut $this->data contient un objet User.
     */
     
    private function renderUserTweets(){
        $homeHTML="";
        foreach($this->data as $tweet){
            $text = $tweet['text'];
            $homeHTML.= <<<EOT
            <p> $text </p>
            <hr>
EOT;
        }
        return $homeHTML;
    }
  
    /* Méthode renderViewTweet 
     * 
     * Rréalise la vue de la fonctionnalité affichage d'un tweet
     * Retourne le fragment HTML qui réalise l'affichage d'un tweet
     * en particulié
     *
     * L'attribut $this->data contient un objet Tweet
     */

    private function renderViewTweet(){
        $textTweet = $this->data['text'] ;
        return <<<EOT
        <div class="tweet">
        <div class="tweet-text"> $textTweet </div>
        </div>
EOT;
    }



    /* Méthode renderPostTweet
     *
     * Realise la vue de régider un Tweet
     * Retourne la framgment HTML qui dessine un formulaire pour la rédaction
     * d'un tweet, l'action (bouton de validation) du formulaire est la route "/send/"
     */
    protected function renderPostTweet(){
        $actionForm = $this->router->urlFor("/send");
        return <<<EOT
        <form action ="$actionForm" method="post">
	        <textarea cols="30" rows="2" name="text">Enter Tweet...</textarea><br /> 
	        <button type="submit">Send</button>
        </form>
EOT;
    }

    protected function renderLogin(){
        $actionForm = $this->router->urlFor("/checklogin");
        return <<<EOT
        <form action="$actionForm" method="post">
        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="password">
        <button type="submit">Connect</button>
EOT;

    }

    protected function renderFollowers(){
        $htmlFollower = "";
        foreach($this->data as $follower){
            $htmlFollower.='<div class="follower">'.$follower->username.'</div>';
        }
        return $htmlFollower;
    }

    protected function renderSignup(){
        $actionForm = $this->router->urlFor("/checksignup");
        return <<<EOT
        <form action="$actionForm" method="post">
        Fullname : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="fullname" placeholder="Fullname">
        <br>
        Username : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="username" placeholder="Username">
        <br>
        Password : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="password" name="password" placeholder="password">
        <br>
        Retype password : <input type="password" name="retypepassword" placeholder="retype password">
        <br>
        <button type="submit">Signup</button>
EOT;
    }

    /* Méthode renderBody
     *
     * Retourne la framgment HTML de la balise <body> elle est appelée
     * par la méthode héritée render.
     *
     */

    protected function renderBody($selector){
        $html = <<<EOT
        <!DOCTYPE html>
        <html lang="fr">
            <head>
                <meta charset="utf-8">
                <title> Tuiteur </title>
                <link rel="stylesheet" href="html/style.css">
            </head>
EOT;
        $section = "";
        switch ($selector) {
            case "home":
                $sectionContent = $this->renderHome();
                break;
            case "userTweet":
                $sectionContent = $this->renderUserTweets();
                break;
            case "viewTweet":
                $sectionContent = $this->renderViewTweet();
                break;
            case "postTweet":
                $sectionContent = $this->renderPostTweet();
                break;
            case "login":
                $sectionContent= $this->renderLogin();
                break;
            case "followers":
                $sectionContent = $this->renderFollowers();
                break;
            case "signup":
                $sectionContent = $this->renderSignup();
                break;
            default:
                $sectionContent = $this->renderHome();
        }
        echo $html."<body><header>".$this->renderHeader()."</header>"
        ."<section>".$sectionContent."</section>"
        ."<footer>".$this->renderFooter()."</footer></body>" ;
        }

        /*
         * voire la classe AbstractView
         * 
         */












    
}
