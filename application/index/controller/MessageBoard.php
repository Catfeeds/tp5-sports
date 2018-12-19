<?php
namespace app\index\controller;

use think\Controller;
use think\Exception;
use think\Db;
use think\Loader;
use think\Session;
use think\Config;
use think\Request;

class MessageBoard extends controller{

    public function index(){
       $request = Request::instance();
      $post = $request->param();

      // $id = $post['id'];

      // if (!$id) {
      // 	return;
      // }

      $content = Db::name("NewsContent")->find();
      $hot_news = Db::name("HotNews")->find();
     	$videos = Db::name("Videos")->select();
      $list = Db::name("IndexArticle")->select();
      $videos_top = Db::name("Videos")->where('is_top','eq',1)->find();


     	$this->view->assign('content', $content);
     	$this->view->assign('videos', $videos);
     	$this->view->assign('hot_news', $hot_news);
      $this->view->assign('list', $list);
      $this->view->assign('videos_top', $videos_top);

      return $this->view->fetch();
    }

    public function detail (){

      $request = Request::instance();
      $post = $request->param();

      $content = Db::name("NewsContent")->find();
      $hot_news = Db::name("HotNews")->find();
      $videos = Db::name("Videos")->select();
     $videos_top = Db::name("Videos")->where('is_top','eq',1)->find();

      $this->view->assign('content', $content);
      $this->view->assign('videos', $videos);
      $this->view->assign('hot_news', $hot_news);
      $this->view->assign('videos_top', $videos_top);

      $id = $post['id'];

      if (!$id) {
        return show(0, '不存在id');
      }

      $comments = Db::name("IndexComment")->where("article_id", $id)->select();
      $article = Db::name("IndexArticle")->where("id", $id)->find();


      $this->view->assign('comments', $comments);
      $this->view->assign('article', $article);



      return $this->view->fetch();
    }

    public function add () {
      $request = Request::instance();
      $post = $request->param();

      $uid = Session::get('UID');

      if (!$uid) {
        return show(2, '请登录');
      }

      $account = Db::name("AdminUser")->where("id", $uid)->find();

      if (!$account) {
        return show(0, '账号错误');
      }

      $data = [
          'article_id' => $post['article_id'],
          'content' => $post['editorValue'],
          'create_time' => time(),
          'username' => $account['realname']
      ];

      $res = Db::name("IndexComment")->insert($data);

      if ($res) {
        return show(1, '评论成功');
      } else {
        return show(1, '评论失败');
      }

    }

}
