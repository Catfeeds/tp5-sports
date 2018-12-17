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

     	$videos = Db::name("Videos")->select();
      $china_teams = Db::name("ChinaTeam")->select();
      
     	$this->view->assign('videos', $videos);
      $this->view->assign('china_teams', $china_teams);


      return $this->view->fetch();
    }

    public function detail () {
      $request = Request::instance();
      $post = $request->param();

      $id = $post['id'];

      if (!$id) {
        return show(0, '不存在id');
      }

      $china_team_detail = Db::name("ChinaTeamDetail")->where("team_id", $id)->find();
      $videos = Db::name("Videos")->select();
      $china_teams = Db::name("ChinaTeam")->where("id", $id)->find();

      $this->view->assign('china_team_detail', $china_team_detail);
      $this->view->assign('videos', $videos);
      $this->view->assign('china_teams', $china_teams);

      return $this->view->fetch();
    }

}
