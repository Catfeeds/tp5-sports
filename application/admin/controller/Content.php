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

class Content extends Controller
{
    use \app\admin\traits\controller\Controller;

    public function index() {
        return $this->view->fetch();        
    }

    public function AddGraph() {
        if ($this->request->isAjax() && $this->request->isPost()) {
            $request = Request::instance();
            $post = $request->param();
            $content = $post['name'];

            $uid = Session::get('UID');

            $flag = 1;

            foreach ($content as $value) {

                $data = [
                    'type' => 'bar',
                    'content' => json_encode($value)
                ];

                $graph_id = Db::name("AdminGraph")->insertGetId($data);

                if (!$graph_id) {
                    $flag = 0;
                }

                $relation = [
                    'uid' => $uid, 
                    'graph_id' => $graph_id
                ];

                $res = Db::name("AdminGraphReletion")->insert($relation);

                if (!$res) {
                    $flag = 0;
                }

            }

            if ($flag == 1) {
                return ajax_return($content, '上传成功', 1);
            } else {
                return ajax_return(null, '上传失败', 0);
            } 

        } else {
            throw new Exception("非法请求");
        }
    }

    public function add() {
        return $this->view->fetch();
    }


}