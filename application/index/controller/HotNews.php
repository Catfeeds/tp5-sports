<?php
namespace app\index\controller;

use think\Controller;
use think\Exception;
use think\Db;
use think\Loader;
use think\Session;
use think\Config;
use think\Request;

class HotNews extends controller{

    public function index(){
      $request = Request::instance();
      $post = $request->param();

      // $id = $post['id'];

      // if (!$id) {
      // 	return;
      // }

      $content = Db::name("NewsContent")->find();
      $hot_news = Db::name("HotNews")->select();
     	$videos = Db::name("Videos")->select();
      $videos_top = Db::name("Videos")->where('is_top','eq',1)->find();

     	$this->view->assign('content', $content);
     	$this->view->assign('videos', $videos);
     	$this->view->assign('hot_news', $hot_news);
      $this->view->assign('videos_top', $videos_top);

      return $this->view->fetch();
    }

}
