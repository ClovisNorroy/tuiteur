<?php

require_once "vendor/autoload.php";
require_once 'src/mf/utils/ClassLoader.php';

$loader = new mf\utils\ClassLoader('src');
$loader->register();

$config = parse_ini_file("conf/config.ini");
//spl_autoload_register();

use tweeterapp\model\User as User ;
use tweeterapp\model\Tweet;
use mf\router\Router;

$router = new \mf\router\Router();
$db = new Illuminate\Database\Capsule\Manager();

$db->addConnection($config);
$db->setAsGlobal();
$db->bootEloquent();

$router->addRoute('maison',
    '/home/',
    '\tweeterapp\control\TweeterController',
    'viewHome');

$router->setDefaultRoute('/home/');

$router->addRoute('home', '/home', '\tweeterapp\control\TweeterController', 'viewHome');
$router->addRoute('singletweet', '/tweet', '\tweeterapp\control\TweeterController', 'viewTweet');
$router->addRoute("author", '/author', '\tweeterapp\control\TweeterController', 'viewUserTweets');
$router->setDefaultRoute('/home/');

$router->run();

$router->urlFor("/tweet/", ['id'=> 51]);




/*
$lignes = Tweet::select('id', 'score')
            ->where('score', '>', '0')
            ->get();
$tuit = Tweet::where('id', '=', 49)->first();
$author = $tuit->author()->first();
//    echo $author;

$tuits = $author->tweets()->get();
$tuit63 = Tweet::where('id', '=', '63')->first();
$liker = $tuit63->likedBy()->first();

//echo $liker;

$tuitLiked = $liker->liked()->first();
//echo $tuitLiked;

$followed = $liker->follows()->get();
$user9 = User::select()->where('id', '=', '9')->first();
$follower = $user9->followedBy()->first();
//echo $follower;
//foreach ($followed as $f) {
//    echo $f;
//}

//$ctrl = new tweeterapp\control\TweeterController();
//echo ("<p>".$ctrl->viewHome()."<\p>");

$author2 = User::select()->where('id', '=', 1)->first();
$tweets = $author2->Tweets()->get();

$tweets = Tweet::select()->get();
var_dump($_SERVER);
*/
