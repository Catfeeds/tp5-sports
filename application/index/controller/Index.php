<?php
namespace app\index\controller;

use think\Controller;
use think\Exception;
use think\Db;
use think\Loader;
use think\Session;
use think\Config;
use think\Request;

class Index extends controller{

    /**
     * @var array 设置当前操作的前置方法
     */
    // protected $beforeActionList = [
    //     'first',
    // ];

    // public function first(){
    //     echo '前置方法';
    // }

    /**
     * @desc 初始化
     * 需要继承 controller基类才可以使用
     */
    // public function _initialize(){
    //     echo "<pre>";
    //     var_dump('初始化函数');
    //     parent::_initialize();
    // }

    public function index(){

        $videos = Db::name("Videos")->where('is_top','neq',1)->select();
        $videos_top = Db::name("Videos")->where('is_top', 1)->find();
        $hot_news = Db::name("HotNews")->where('is_top','neq',1)->select();
        $hot_news_top = Db::name("HotNews")->where('is_top', 1)->find();

        $games = Db::name("Games")->select();

        $club_infos = Db::name("ClubInfo")->select();

        $china_teams = Db::name("ChinaTeam")->select();




        $this->view->assign('videos_top', $videos_top);
        $this->view->assign('videos', $videos);

        $this->view->assign('hot_news', $hot_news);
        $this->view->assign('hot_news_top', $hot_news_top);

        $this->view->assign('games', $games);

        $this->view->assign('club_infos', $club_infos);
        $this->view->assign('china_teams', $china_teams);
        




        // dump($games);
        // exit;


        return $this->view->fetch();
    }


    public function ruleToMethod(){
        //var_dump('路由到方法');
        $arr = ['路由到方法'];
        $request = Request::instance();
        var_dump(
            $request->url(true),
            $request->module(),
            $request->controller(),
            $request->action());

        //return json($arr,200);
    }
}
