<?php

namespace tweeterapp\control;
use tweeterapp\model\Tweet ;
use tweeterapp\model\User;
use tweeterapp\view\TweeterView;
use tweeterapp\Follow;

/* Classe TweeterController :
 *  
 * Réalise les algorithmes des fonctionnalités suivantes: 
 *
 *  - afficher la liste des Tweets 
 *  - afficher un Tweet
 *  - afficher les tweet d'un utilisateur 
 *  - afficher la le formulaire pour poster un Tweet
 *  - afficher la liste des utilisateurs suivis 
 *  - évaluer un Tweet
 *  - suivre un utilisateur
 *   
 */

class TweeterController extends \mf\control\AbstractController {


    /* Constructeur :
     * 
     * Appelle le constructeur parent
     *
     * c.f. la classe \mf\control\AbstractController
     * 
     */
    
    public function __construct(){
        parent::__construct();
    }


    /* Méthode viewHome : 
     * 
     * Réalise la fonctionnalité : afficher la liste de Tweet
     * 
     */
    
    public function viewHome(){
        $tuits = Tweet::select()->get();
        foreach ($tuits as $tuit){
            $author = User::where('id', '=', $tuit['author'])->first();
            $tuit['authorNickName'] = $author['username'];
            $alltuits[] = $tuit;
        }
        $vue = new \tweeterapp\view\TweeterView($alltuits);
        $vue->render("home");
    }

    public function viewHomeLogged(){
        $user = User::select()->where('id', '=', 12)->first();
        $followers = $user->followedBy()->get();
        $followerArray = [];
        foreach($followers as $follower){
            array_push($followerArray, $follower->id);
        }
        $tuits = Tweet::select()->whereIn('author', $followerArray)->get();

        foreach ($tuits as $tuit){
            $author = User::where('id', '=', $tuit['author'])->first();
            $tuit['authorNickName'] = $author['username'];
            $alltuits[] = $tuit;
        }
        $vue = new \tweeterapp\view\TweeterView($alltuits);
        $vue->render("home");
    }

    public function viewTweet(){
        $tweet = Tweet::select()->where('id', '=', $_GET["id"])->first();
        $tweeterView = new TweeterView($tweet);
        $tweeterView->render("singleTweet");
    }

    public function viewUserTweets(){
        if(isset($_GET["id"])){
            $tweets = Tweet::select()->where('author', '=', $_GET["id"])->get();
            $tweetView = new TweeterView($tweets);
            $tweetView->render("userTweet");
        }
    }

    public function viewFollowers(){
        $user = User::select()->where('id', '=', 12)->first();
        $followers = $user->followedBy()->get();
        $tweeterView = new TweeterView($followers);
        $tweeterView->render("followers");
    }

    public function postTweet(){
        $emptyView = new TweeterView("");
        $emptyView->render("postTweet");
    }
//TODO Filtrer
    public function sendTweet(){
        $tweetToSend = new Tweet;
        $tweetToSend->text = $_POST['text'];
        $tweetToSend->author = $_SESSION['user_login'];
        $tweetToSend->save();
        $this->viewHome();
    }
}
