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

class InfoDownload extends Controller
{
    use \app\admin\traits\controller\Controller;

    public function index() {
        $infos = Db::name("ClubInfo")->where('status', 'neq', 1)->paginate(5);
        
        // 获取分页显示
        $page = $infos->render();

        $this->view->assign('infos', $infos);
        $this->view->assign('page', $page);

        return $this->view->fetch();
    }

    public function add() {
        if ($this->request->isAjax() && $this->request->isPost()) {
            $request = Request::instance();
            $post = $request->param();

            if($post['infos_id']) {
                return $this->save($post);
            }

            $data = [
                'title' => $post['title'],
                'thumb' => $post['thumb'],
                'intro' => $post['intro'],
                'time' => time(),
                'status' => 0
            ];

            $newsId = Db::name("ClubInfo")->insertGetId($data);

            if($newsId) {
                $newsContentData['content'] = $post['content'];
                $newsContentData['club_id'] = $newsId;
                $cId = Db::name("ClubDetail")->insert($newsContentData);
                if($cId){
                    return show(1,'新增成功');
                }else{
                    return show(1,'主表插入成功，副表插入失败');
                }
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

        $news = Db::name("ClubInfo")->where('id',$id)->find();
        $content = Db::name("ClubDetail")->where('club_id',$id)->find();


        $this->view->assign('infos', $news);
        $this->view->assign('content', $content);

        return $this->view->fetch();
    }

    public function save($data) {
        $newsId = $data['infos_id'];

        $res_data = [
            'title' => $data['title'],
            'thumb' => $data['thumb'],
            'intro' => $data['intro'],
            'time' => time(),
            'status' => 0
        ];

        try {
            $res_id = Db::name("ClubInfo")->where("id", $newsId)->update($res_data);

            $newsContentData['content'] = $data['content'];

            $condId =  Db::name("ClubDetail")->where("club_id", $newsId)->update($newsContentData);

            if($res_id === false || $condId === false) {
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

                $news = Db::name("ClubInfo")->where('id',$id)->update($data);
                
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