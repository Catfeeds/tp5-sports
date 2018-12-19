<?php
namespace app\index\controller;

use think\Controller;
use think\Exception;
use think\Db;
use think\Loader;
use think\Session;
use think\Config;
use think\Request;

class VideoDetail extends controller{

    public function index(){
      $request = Request::instance();
      $post = $request->param();

      $id = $post['id'];

      if (!$id) {
      	return;
      }

      $content = Db::name("VideoContent")->where('video_id',$id)->find();

     	$videos = Db::name("Videos")->select();

      $videosDetail = Db::name("Videos")->where('id',$id)->find();


      $videos_top = Db::name("Videos")->where('is_top','eq',1)->find();
      $hot_news = Db::name("HotNews")->find();

     	$this->view->assign('content', $content);
     	$this->view->assign('videos', $videos);
      $this->view->assign('videosDetail', $videosDetail);


     	$this->view->assign('hot_news', $hot_news);
      $this->view->assign('videos_top', $videos_top);

      return $this->view->fetch();
    }

}
