<?php
namespace app\index\controller;

use think\Controller;
use think\Exception;
use think\Db;
use think\Loader;
use think\Session;
use think\Config;
use think\Request;

class GameDetail extends controller{

    public function index(){
      $request = Request::instance();
      $post = $request->param();

      $id = $post['id'];

      if (!$id) {
      	return;
      }

      $content = Db::name("GamesDetail")->where('games_id',$id)->find();
      $games = Db::name("Games")->where('id',$id)->find();
      $videos = Db::name("Videos")->where('is_top','neq',1)->select();
      $videos_top = Db::name("Videos")->where('is_top','eq',1)->find();


     	$this->view->assign('content', $content);
     	$this->view->assign('videos', $videos);
     	$this->view->assign('games', $games);
      $this->view->assign('videos_top', $videos_top);


      return $this->view->fetch();
    }

}
