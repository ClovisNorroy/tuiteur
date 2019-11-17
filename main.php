<?php
session_start(); //TODO Implement session TimeOut
require_once "vendor/autoload.php";
require_once "src/mf/utils/ClassLoader.php";

$loader = new mf\utils\ClassLoader('src');
$loader->register();

$config = parse_ini_file("conf/config.ini");

use mf\router\Router;
use \tweeterapp\auth\TweeterAuthentification;


$router = new Router();
$db = new Illuminate\Database\Capsule\Manager();

$db->addConnection($config);
$db->setAsGlobal();
$db->bootEloquent();

$router->addRoute('home',
    '/home/',
    '\tweeterapp\control\TweeterController',
    'viewHome');
$router->addRoute(
    'home',
    '/home',
    '\tweeterapp\control\TweeterController',
    'viewHome');
$router->addRoute(
    'singletweet',
    '/tweet',
    '\tweeterapp\control\TweeterController',
    'viewTweet');
$router->addRoute(
    'author',
    '/author',
    '\tweeterapp\control\TweeterController',
    'viewUserTweets');
$router->addRoute(
    "post",
    "/post",
    '\tweeterapp\control\TweeterController',
    'postTweet',
    TweeterAuthentification::ACCESS_LEVEL_USER);
$router->addroute(
    "send",
    "/send",
    '\tweeterapp\control\TweeterController',
    'sendTweet',
    TweeterAuthentification::ACCESS_LEVEL_USER);
$router->addRoute(
    'login',
    '/login',
    '\tweeterapp\control\TweeterAdminController',
    'postTweet');
$router->addRoute(
    'checkLogin',
    '/checklogin',
    '\tweeterapp\control\TweeterAdminController',
    'checkLogin');
$router->addRoute(
    'logout',
    '/logout',
    '\tweeterapp\control\TweeterAdminController',
    'logout');
$router->addRoute(
    'signup',
    '/signup',
    'tweeterapp\control\TweeterAdminController',
    'signup');
$router->addRoute(
    'checksignup',
    '/checksignup',
    'tweeterapp\control\TweeterAdminController',
    'checkSignup');
$router->addRoute(
    'homeLogged',
    '/homeLogged',
    'tweeterapp\control\TweeterController',
    'viewHomeLogged');

$router->setDefaultRoute('/home');

$router->run();