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
        $user = User::select()->where('id', '=', $_GET["id"])->first();
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
        if(isset($_GET['id']))
        $user = User::select()->where('id', '=', $_GET["id"])->first();
        if(isset($_GET['userid']))
            $user = User::find($_GET['userid']);
        $followers = $user->followedBy()->get();
        $tweeterView = new TweeterView($followers);
        $tweeterView->render("followers");
    }

    public function viewInfluence(){
        $usersOrderedByNbrFollower = User::select()->orderBy("followers", "desc")->get();
        $tweeterView = new TweeterView($usersOrderedByNbrFollower);
        $tweeterView->render("influence");
    }

    public function postTweet(){
        $emptyView = new TweeterView("");
        $emptyView->render("postTweet");
    }
//TODO Filtrer
    public function sendTweet(){
        $user = User::select("id")->where("username", "like", "%".$_SESSION['user_login']."%")->first();
        $tweetToSend = new Tweet;
        $tweetToSend->text = addslashes($_POST['text']);
        $tweetToSend->author = $user->id;
        $tweetToSend->save();
        $this->viewHome();
    }

    public function sphereInfluence(){
        $users = User::all();
        $userToSphere = [];
        foreach ($users as $user){
            $queue = []; // users to check
            $done = []; // users already checked
            array_push($done, $user->id);
            $followers = $user->followedBy()->get();
            foreach($followers as $foo){
                array_push($queue, $foo->id);
                array_push($done, $foo->id);
            }


            while(sizeof($queue)>0){
                $userToCheck = User::find(array_shift($queue));
                $followersToCheck = $userToCheck->followedBy()->get();
                foreach($followersToCheck as $userToCheck){
                    if(!in_array($userToCheck->id, $done)){ //si user pas déjà check
                        array_push($queue, $userToCheck->id);// ajout dans queue
                        array_push($done, $userToCheck->id);
                    }
                }
            }
        $userToSphere[$user->id]=sizeof($done)-1; // -1 pour enlever pour enlever le follow sur soi même
        }
        $tweeterView = new TweeterView($userToSphere);
        $tweeterView->render("sphere");
    }


    public function updateCountFollowers(){
        $users = User::all();
        foreach ($users as $user){
            $userFollowers = $user->followedBy()->get();
            $user->followers = sizeof($userFollowers);
            $user->save();
        }
        echo "Followers count updated !";
    }
}
