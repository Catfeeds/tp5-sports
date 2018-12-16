<?php
namespace app\index\controller;

use think\Controller;
use think\Exception;
use think\Db;
use think\Loader;
use think\Session;
use think\Config;
use think\Request;

class NewsDetail extends controller{

    public function index(){

  		$request = Request::instance();
      $post = $request->param();

      $id = $post['id'];

      if (!$id) {
      	return;
      }

      $content = Db::name("NewsContent")->where('news_id',$id)->find();

      $hot_news = Db::name("HotNews")->where('id',$id)->find();


     	$videos = Db::name("Videos")->where('is_top','neq',1)->select();

     	$this->view->assign('content', $content);
     	$this->view->assign('videos', $videos);
     	$this->view->assign('hot_news', $hot_news);

      return $this->view->fetch();

    }


}
