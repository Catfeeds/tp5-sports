<?php
namespace app\index\controller;

use think\Controller;
use think\Exception;
use think\Db;
use think\Loader;
use think\Session;
use think\Config;
use think\Request;

class ChinaTeam extends controller{

    public function index () {
      $request = Request::instance();
      $post = $request->param();

      // $id = $post['id'];

      // if (!$id) {
      // 	return;
      // }

     	$videos = Db::name("Videos")->select();
      $china_teams = Db::name("ChinaTeam")->select();
      
     	$this->view->assign('videos', $videos);
      $this->view->assign('china_teams', $china_teams);


      return $this->view->fetch();
    }

    public function detail () {
      return $this->view->fetch();
    }

}
