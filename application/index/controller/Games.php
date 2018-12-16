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

      // $id = $post['id'];

      // if (!$id) {
      // 	return;
      // }

      $content = Db::name("NewsContent")->find();
     	$videos = Db::name("Videos")->select();
      $games = Db::name("Games")->select();


     	$this->view->assign('content', $content);
     	$this->view->assign('videos', $videos);
      $this->view->assign('games', $games);


      return $this->view->fetch();
    }

}
