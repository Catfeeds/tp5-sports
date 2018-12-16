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

class VideoContent extends Controller
{
    use \app\admin\traits\controller\Controller;

    public function index() {
        $videos = Db::name("Videos")->paginate(5);
        
        // 获取分页显示
        $page = $videos->render();

        $this->view->assign('videos', $videos);
        $this->view->assign('page', $page);

        return $this->view->fetch();
    }

    public function add() {
        if ($this->request->isAjax() && $this->request->isPost()) {
            $request = Request::instance();
            $post = $request->param();

            // if($post['video_id']) {
            //     return $this->save($post);
            // }

            if(!isset($post['title']) || !$post['title']) {
                return show(0,'标题不存在');
            }
            if(!isset($post['small_title']) || !$post['small_title']) {
                return show(0,'短标题不存在');
            }
            if(!isset($post['content']) || !$post['content']) {
                return show(0,'content不存在');
            }

            if($post['video_id'] != 0) {
                return $this->save($post);
            }

            $data = [
                'video_name' => $post['title'],
                'video_thumb' => $post['thumb'],
                'video_info' => $post['small_title'],
                'is_top' => $post['is_top'],
                'video_url' => $post['video_url'],
                'video_time' => time(),
            ];

            $videoId = Db::name("Videos")->insertGetId($data);

            if($videoId) {
                $videoContentData['content'] = $_POST['content'];
                $videoContentData['video_id'] = $videoId;
                $cId = Db::name("VideoContent")->insert($videoContentData);
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

        $news = Db::name("Videos")->where('id',$id)->find();
        $content = Db::name("VideoContent")->where('news_id',$id)->find();


        $this->view->assign('news', $news);
        $this->view->assign('content', $content);

        return $this->view->fetch();
    }

    public function save($data) {
        $newsId = $data['news_id'];

        $data = [
            'video_name' => $post['title'],
            'video_thumb' => $post['thumb'],
            'video_info' => $post['small_title'],
            'is_top' => $post['is_top'],
            'video_time' => time(),
        ];

        try {
            $res_id = Db::name("Videos")->where("id", $newsId)->update($res_data);

            $newsContentData['content'] = $data['content'];

            $condId =  Db::name("VideoContent")->where("news_id", $newsId)->update($newsContentData);

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