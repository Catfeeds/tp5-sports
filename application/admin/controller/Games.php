<?php
/**
 * tpAdmin [a web admin based ThinkPHP5]
 *
 * @author yuan1994 <tianpian0805@gmail.com>
 * @link http://tpadmin.yuan1994.com/
 * @copyright 2016 yuan1994 all rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

//------------------------
// 用户控制器
//-------------------------

namespace app\admin\controller;

\think\Loader::import('controller/Controller', \think\Config::get('traits_path') , EXT);

use app\admin\Controller;
use think\Exception;
use think\Db;
use think\Loader;
use think\Session;
use think\Config;
use think\Request;

class Games extends Controller
{
    use \app\admin\traits\controller\Controller;

    public function index() {
        $games = Db::name("Games")->paginate(5);
        
        // 获取分页显示
        $page = $games->render();

        $this->view->assign('games', $games);
        $this->view->assign('page', $page);

        return $this->view->fetch();
    }

    public function add() {
        if ($this->request->isAjax() && $this->request->isPost()) {
            $request = Request::instance();
            $post = $request->param();

            if($post['games_id'] != 0) {
                return $this->save($post);
            }

            $data = [
                'gamename' => $post['gamename'],
                'starttime' => $post['starttime'],
                'address' => $post['address'],
                'team1' => $post['team1'],
                'team2' => $post['team2'],
                'points' => $post['points'],
                'thumb1' => $post['thumb1'],

            ];

            $newsId = Db::name("Games")->insertGetId($data);

            if($newsId) {
                return show(1,'新增成功');
            } else {
                return show(0,'添加失败');
            }
        } else {
            return $this->view->fetch();
        }
        
    }

    public function edit() {
        $request = Request::instance();
        $post = $request->param();

        $id = $post['id'];

        if (!$id) {
            return show(0, 'ID 没有');
        }

        $games = Db::name("Games")->where('id',$id)->find();


        $this->view->assign('games', $games);

        return $this->view->fetch();
    }

    public function save($data) {
        $newsId = $data['games_id'];

        $res_data = [
            'gamename' => $data['gamename'],
            'starttime' => $data['starttime'],
            'address' => $data['address'],
            'team1' => $data['team1'],
            'team2' => $data['team2'],
            'points' => $data['points'],
            'thumb1' => $data['thumb1'],
        ];

        try {
            $res_id = Db::name("Games")->where("id", $newsId)->update($res_data);

            if($res_id === false ) {
                return show(0, '更新失败');
            }

            return show(1, '更新成功');
        }catch(Exception $e) {
            return show(0, $e->getMessage());
        }
    }

    public function setStatus() {
        try {
            if ($this->request->isAjax() && $this->request->isPost()) {
                $request = Request::instance();
                $post = $request->param();

                $id = $post['id'];
                $status = $post['status'];

                if (!$id) {
                    return show(0, 'ID不存在');
                }

                $data = [
                    'status' => 1
                ];

                $news = Db::name("HotNews")->where('id',$id)->update($data);
                
                if ($news) {
                    return show(1, '操作成功');
                } else {
                    return show(0, '操作失败');
                }
            }

            return show(0, '没有提交的内容');
        }catch(Exception $e) {
            return show(0, $e->getMessage());
        }
    }


}