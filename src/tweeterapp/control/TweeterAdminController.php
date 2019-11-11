<?php


namespace tweeterapp\control;


use mf\control\AbstractController;
use tweeterapp\view\TweeterView;

class TweeterAdminController extends AbstractController
{
    public function postTweet(){
        $emptyView = new TweeterView("");
        $emptyView->render("login");
    }
}