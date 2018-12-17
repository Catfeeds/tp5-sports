<?php
namespace app\index\controller;

use think\Controller;
use think\Exception;
use think\Db;
use think\Loader;
use think\Session;
use think\Config;
use think\Request;

class InfoDownload extends controller{

    public function index(){
       $request = Request::instance();
      $post = $request->param();

      // $id = $post['id'];

      // if (!$id) {
      // 	return;
      // }

      $videos = Db::name("Videos")->select();
      $infos = Db::name("ClubInfo")->select();

      $this->view->assign('videos', $videos);
      $this->view->assign('infos', $infos);

      return $this->view->fetch();
    }

    public function detail () {
      $request = Request::instance();
      $post = $request->param();

      $videos = Db::name("Videos")->select();
      
      
      

      $id = $post['id'];

      if (!$id) {
        return show(0, '不存在id');
      }

      $infos = Db::name("ClubInfo")->where('id',$id)->find();
      $club_info = Db::name("ClubDetail")->where("club_id", $id)->find();

      $this->view->assign('club_info', $club_info);
      $this->view->assign('videos', $videos);
      $this->view->assign('infos', $infos);

      return $this->view->fetch();
    }

}
