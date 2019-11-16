<?php


namespace tweeterapp\control;


use mf\auth\Authentification;
use mf\auth\exception\AuthentificationException;
use mf\control\AbstractController;
use mf\router\Router;
use tweeterapp\auth\TweeterAuthentification;
use tweeterapp\view\TweeterView;
use tweeterapp\model\User;

class TweeterAdminController extends AbstractController
{
    public function postTweet(){
        $emptyView = new TweeterView("");
        $emptyView->render("login");
    }

    public function checkLogin(){
        if(isset($_POST['username']) && isset($_POST['password'])){
            $tweeterAuth = new TweeterAuthentification();
            $tweeterAuth->loginUser($_POST['username'], $_POST['password']);
            if(isset($_SESSION['username'])) {
                echo "Session set";
                //$tweeterView = new TweeterView(User::select('follower')->where('followee', 'like', '%'.$_POST['username'].'%'));
                $tweeterView = new TweeterView(User::select('follower')->where('followee', '=', '9'));
                $tweeterView->render("followers");
            }
            else
                Router::executeRoute("login");
        }
    }

    public function logout(){
        $auth = new Authentification();
        $auth->logout();
    }

    public function signup(){
        $emptyView = new TweeterView("");
        $emptyView->render("signup");
    }

    public function checkSignup(){
        if(isset($_POST['username']) &&
        isset($_POST['password']) &&
        isset($_POST['retypepassword']) &&
        isset($_POST['fullname']) &&
        $_POST['password'] == $_POST['retypepassword']){
            echo "Good" ;
            $tweeterAuth = new TweeterAuthentification();
                $tweeterAuth->createUser(
                    $_POST['username'],
                    password_hash($_POST['password'], PASSWORD_DEFAULT),
                    $_POST['fullname']);
                echo 'It did work' ;
            Router::executeRoute("home");
        }else
            Router::executeRoute("signup");
    }
}