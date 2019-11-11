<?php


namespace tweeterapp\control;


use mf\auth\Authentification;
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
                $tweeterView = new TweeterView(User::select('follower')->where('followee', 'like', '%'.$_POST['username'].'%'));
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
}