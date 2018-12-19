<?php
namespace app\index\controller;

use think\Controller;
use think\Exception;
use think\Db;
use think\Loader;
use think\Session;
use think\Config;
use think\Request;

class Games extends controller{

    public function index(){
      $request = Request::instance();
      $post = $request->param();

      $content = Db::name("NewsContent")->find();
     	$videos = Db::name("Videos")->select();
      $games = Db::name("Games")->select();
      $videos_top = Db::name("Videos")->where('is_top','eq',1)->find();

     	$this->view->assign('content', $content);
     	$this->view->assign('videos', $videos);
      $this->view->assign('games', $games);
      $this->view->assign('videos_top', $videos_top);


      return $this->view->fetch();
    }

}
